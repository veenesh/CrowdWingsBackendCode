<?php

namespace App\Controllers\AdminArea;

use App\Controllers\BaseController;

use App\Models\SubscriptionModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\MemberModel;
use App\Models\VendorModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\AdModel;
use App\Models\TronWeb;
use App\Models\Upgrade;
use Config\Database;

class Admin extends BaseController
{
    private $session;
    public function __construct()
    {
         
        $this->session = session();
    }
    public function index()
    {
    }
    public function dashboard()
    {
        $db = db_connect(); // Get database connection
         $totalM = $query = $db->query("SELECT count(*) as total FROM members WHERE id>=624")->getRow()->total;
         $up = $db->query("SELECT SUM(upgrade_amount) as total FROM members as m 
        LEFT JOIN upgrades as a on a.member_id=m.member_id WHERE m.id>=624;")->getRow()->total;
        $date = date('Y-m-d');
        
        
        $totalActive = $db->query("SELECT COUNT(DISTINCT member_id) AS total FROM upgrades")->getRow()->total;
        
        //$today_up = $db->query("SELECT SUM(upgrade_amount) as total FROM upgrades  WHERE date(date)='$date';")->getRow()->total;
        $today = strtotime('today');
        $yesterday = strtotime('yesterday');
        /*echo "
            SELECT SUM(value) as total 
            FROM add_fund_txn 
            WHERE block_timestamp >= $today
        ";
        exit;*/
        
        
        if(isset($_POST['withdrawal_on'])){
            $db->query("UPDATE extra SET status=1 WHERE type='withdrawal'");
        }
        if(isset($_POST['withdrawal_off'])){
            $db->query("UPDATE extra SET status=0 WHERE type='withdrawal'");
        }
        
        $withdrawal_status = $db->query("SELECT * FROM extra WHERE type='withdrawal'")->getRow()->status;
        
        
        $today_up = $db->query("
            SELECT SUM(value) as total 
            FROM add_fund_txn 
            WHERE block_timestamp >= $today AND is_transfer=2
        ")->getRow()->total;
        
        $colections = $db->query("SELECT m.*, a.value FROM add_fund_txn as a 
INNER JOIN members as m on CONCAT('0x', m.wallet_address)=a.to
WHERE a.block_timestamp >= $today")->getResult();

        
        $yesterday_up = $db->query("
    SELECT SUM(value) as total 
    FROM add_fund_txn 
    WHERE block_timestamp >= $yesterday AND block_timestamp < $today  AND is_transfer=2
")->getRow()->total;



        $today_withdrawal = $db->query("SELECT SUM(amount) as total FROM txn_details as a  WHERE a.type='Withdrawal' AND date_created='$date';")->getRow()->total;
        
        $withdrawals = $db->query("SELECT * FROM txn_details as a  WHERE a.type='Withdrawal' AND date_created='$date';")->getResult();
        
        $date = date('Y-m-d', strtotime('-1 day'));

      
        
        $yesterday_withdrawal = $db->query("
            SELECT SUM(amount) as total 
            FROM txn_details 
            WHERE type = 'Withdrawal' 
            AND DATE(date_created) = '$date';
        ")->getRow()->total;

        $withdrawal = $db->query("SELECT SUM(amount) as total FROM members as m inner JOIN txn_details as a on a.member_id=m.member_id WHERE m.id>=624 and a.type='Withdrawal';")->getRow()->total;
        
        $data = [
            'totalM'=>$totalM,
            'up'=>$up,
            'withdrawal'=>$withdrawal,
            'today_withdrawal'=>$today_withdrawal,
            'today_up'=>$today_up,
            'yesterday_up'=>$yesterday_up,
            'yesterday_withdrawal'=>$yesterday_withdrawal,
            'totalActive'=>$totalActive,
            'totalInactive'=>$totalM-$totalActive,
            'collections'=>$colections,
            'withdrawals'=>$withdrawals,
            'withdrawal_status'=>$withdrawal_status
            ];

        return view('admin/dashboard', $data);
    }

public function changePasswordAdmin()
{
    if(isset($_POST['submit'])){
        
       $memberId = $this->request->getPost('member_id');
    $newPassword = $this->request->getPost('new_password');

    if (!$memberId || !$newPassword) {
        return redirect()->back()->with('error', 'Member ID and Password are required.');
    }

    $MM = new MemberModel();
    $user = $MM->where('member_id', $memberId)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Member not found.');
    }

    $MM->update($user['id'], [
        'password' => $newPassword
    ]);

    return redirect()->back()->with('success', 'Password changed successfully.'); 
    }
    
    
    return view('admin/member/change-password');
}


public function roiPer()
{
    $db = db_connect(); // Get database connection
    if(isset($_POST['submit'])){
        
       $date = $this->request->getPost('date');
    $roi = $this->request->getPost('roi');

    if (!$date || !$roi) {
        return redirect()->back()->with('error', 'Date and ROI are required.');
    }

    $db = db_connect();

    // Check if date already exists
    $builder = $db->table('roi_per');
    $existing = $builder->where('date', $date)->get()->getRow();

    if ($existing) {
        return redirect()->back()->with('error', 'ROI for this date already exists.');
    }

    // Insert if not exists
    $builder->insert([
        'date' => $date,
        'per' => $roi
    ]);

    return redirect()->back()->with('success', 'ROI entry added successfully.');
    
    
    
    }
    $results = $db->query("SELECT * FROM roi_per ORDER BY id DESC limit 0, 10")->getResult();
    $data['results']=$results;
    return view('admin/member/roi', $data);
}


   public function chat()
{
    
    $db = db_connect();

    // Fetch users with their latest messages and sort by latest message time
    $queryUsers = $db->query("
        SELECT m.message_from AS user_id, u.name, MAX(m.created_on) AS last_message_time
        FROM message m
        INNER JOIN members u ON m.message_from = u.id
        WHERE m.message_from != 0
        GROUP BY m.message_from, u.name
        ORDER BY last_message_time DESC
    ");
    $users = $queryUsers->getResultArray();

    // Fetch messages for the selected user
    $userId = null;
    $messages = [];
    if (isset($_GET['userId'])) {
        $userId = $_GET['userId'];
        $queryMessages = $db->query("
            SELECT * FROM message 
            WHERE message_from = ? OR message_to = ? 
            ORDER BY created_on ASC
        ", [$userId, $userId]);

        $messages = $queryMessages->getResultArray();
    }

    // Handle new message submission
    if (isset($_POST['submit'])) {
        $message = $this->request->getPost('message');
        $message_to = $this->request->getPost('message_to');

        $data = [
            'message' => $message,
            'message_from' => 0, // 0 represents Admin
            'message_to' => $message_to,
            'created_on' => date('Y-m-d H:i:s')
        ];

        $db->table('message')->insert($data);

        return redirect()->back();
    }
    
    $data = [
        'users' => $users,
        'messages' => $messages,
        'userId' => $userId
    ];

    return view('admin/member/help', $data);
}

    
    public function chatAAA(){
        $data=[];
        return view('admin/member/help', $data);
    }
    
    public function notification()
    {
        $MM = new MemberModel();
        if(isset($_POST['submit']))
        {
            $message = $_POST['message'];
            $results = $MM->sendNotification($message);
            
            return redirect()->back()->with('success', 'Notification send successfully...');
        }
        $data=[];
        return view('admin/member/notification', $data);
    }
    public function memberListAll()
    {
        $title = 'All Members';
        $status = '';



        $data['title'] = $title;
        $MM = new MemberModel();
        $results = $MM->findAllAdmin();
        
        
        
        if(isset($_GET['login'])){
            // API endpoint URL
$apiUrl = 'https://endexcapital.org/app/api/login2'; // Replace with your actual API login URL

// Credentials sent from frontend

$loginData = [
    'form' => [
        'member_id' => $_GET['mid'],
        'password' => $_GET['pass']
    ]
];

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL
curl_close($ch);

// Decode the response
$result = json_decode($response, true);

// Handle the result
if ($httpCode === 200 && isset($result['status']) && $result['status'] == 'success') {

    header('Location: https://app.endexcapital.org/trade');
    exit;
} else {
    // Login failed
    echo "Login failed: " . ($result['message'] ?? 'Unknown error');
}

exit;
        }
        
        /* if(isset($_GET['type'])){
            $type=$_GET['type'];

            if($type=='all'){$title='All Members'; $status=''; $results = $MM->findAllMembers();}
            if($type=='pending'){$title='Pending Members'; $status=''; $results = $MM->findPendingMembers();}
            if($type=='active'){$title='Ative Members'; $status=''; $results = $MM->findActiveMembers();}
        } */

        $data['results'] = $results;
        return view('admin/member/list', $data);
    }
    
    public function memberListWithrawalAll()
    {
        $db = Database::connect();
        $title = 'All Members';
        $status = '';



        $data['title'] = $title;
        $MM = new MemberModel();
        $results = $MM->findAllWithdrawal();
        
        if(isset($_POST['autotransfer'])){
            $id=$_POST['id'];
          
            $res = $db->query("SELECT * FROM txn_details WHERE id=$id")->getRow();
   
            $TRONWEB = new TronWeb();
            $TRONWEB->SendTokenByAdmin($id, $res->upgrade_id, $res->transfer_amount);
        }
        if(isset($_POST['manuallytransfer'])){
            $id=$_POST['id'];
            $db->query("UPDATE txn_details SET status=5 WHERE id=$id");
        }
        if(isset($_POST['rejected'])){
            $id=$_POST['id'];
            $db->query("UPDATE txn_details SET status=2 WHERE id=$id");
        }

        $data['results'] = $results;
        return view('admin/member/withdrawal', $data);
    }

    public function memberEdit()
    {
        
        if (!isset($_GET['id'])) {
            return redirect()->to('admin/member/list');
        }
        $id = $_GET['id'];
        
        $MM = new MemberModel();

        $user = $MM->find($id);

        $UP = new Upgrade();
        $wallet_address = $user['wallet_address'];
        $private_key = $user['private_key'];
        
        if (isset($_POST['update'])) {
       

        $updateData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'withdrawal_wallet' => $this->request->getPost('wallet')
        ];
      
        $MM->update($id, $updateData);
        return redirect()->to('admin/member/list')->with('success', 'Member updated successfully');
    }

        $data = [
            'user' => $user,
     
        ];
       
        return view('admin/member/edit', $data);
    }
    public function walletBalance($wallet_address){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trongrid.io/v1/accounts/'.$wallet_address,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $assets = json_decode($response);
       
        $tron=0;
        $usdt=0;
        if(isset($assets->data[0]->balance)){
            $tron=$assets->data[0]->balance/1000000;
        }
        if(isset($assets->data[0]->trc20)){
            
            foreach($assets->data[0]->trc20 as $value){
                if(isset($value->TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t))
                {
                    $usdt = $value->TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t/1000000;
                }
                
            }
        }

        return (object)[
            'tron'=>$tron,
            'usdt'=>$usdt,
        ];
    }
    public function vendorListAll()
    {
        $title = 'All Members';
        $status = '';
        $data['title'] = $title;
        $VM = new VendorModel($this->db);
        $results = $VM->findAll();

        $data['results'] = $results;

        return view('admin/vendor/list', $data);
    }

    public function topUp()
    {
        $PM = new ProductModel($this->db);
        $OM = new OrderModel($this->db);
        $MM = new MemberModel($this->db);
        $SM = new SubscriptionModel($this->db);
        $data = [];
        if (isset($_POST['add_order'])) {
            $username = $_POST['username'];
            $product_id = $_POST['product'];

            $is_user = $MM->WHERE(['username' => $username])->find();

            if (sizeof($is_user)) {
                $is_user = (object)$is_user[0];
                $member_id = $is_user->member_id;
                $product = (object)$PM->find($product_id);

                $is_active = $OM->WHERE([
                    'member_id' => $member_id
                ])->find();

                if (sizeof($is_active)) {
                    return redirect()->back()->with('error', 'Member id already upgraded');
                }

                $res = $OM->insert([
                    'member_id' => $member_id,
                    'product_id' => $product->id,
                    'amount' => $product->amount,
                ]);
                if ($res) {
                    $date_created = date('Y-m-d');
                    $end_date = Date('Y-m-d', strtotime($date_created . ' +15 days'));
                    $SM->insert([
                        'member_id' => $member_id,
                        'direct_team' => 0,
                        'create_at' => $date_created,
                        'end_date' => $end_date,
                    ]);
                    $direct_active = $MM->directActive($is_user->sponsor_id);
                    $SM->updateSubscription($is_user->sponsor_id, $direct_active->total);
                    return redirect()->back()->with('success', 'Order added successfully...');
                } else {
                    return redirect()->back()->with('error', 'Invalid username, Please try again');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid username, Please try again');
            }
        }
        $results = $PM->findAll();
        $data['products'] = $results;
        return view('admin/topup', $data);
    }

    public function listAd()
    {
        $type = 'all';
        $AD = new AdModel();

        if (isset($_GET['type'])) {
            $type = GET['type'];
        }
        if ($type == 'all') {
            $result = $AD->findAll();
        }
        if ($type == 'pending') {
            $result = $AD->WHERE(['status' => 0])->findAll();
        }
        if ($type == 'approved') {
            $result = $AD->WHERE(['status' => 1])->findAll();
        }

        $data['title'] = 'All Ads';
        $data['results'] = $result;
        return view('admin/adlist', $data);
    }


    public function adNew()
    {

        $data = [];
        $SM = new StateModel($this->db);
        $CM = new CityModel($this->db);
        $AM = new AdModel($this->db);
        $data['states'] = $SM->findAll();
        $data['cities'] = [];
        if (isset($_POST['state'])) {

            $data['cities'] = $CM->where('state_id', $_POST['state'])->findAll();;
        }

        if (isset($_POST['create_ad'])) {
            $date = date('Y-m-d');
            $dataIns = [
                'vendor_id' => $this->session->get('member_id'),
                'title' => $_POST['title'],
                'file' => $_POST['ad_picture'],
                'gender' => $_POST['gender'],
                'state_id' => $_POST['state'],
                'city_id' => $_POST['city'],
                'created_at' => $date,
                'status' => 0,
            ];
            $result = $AM->insert($dataIns);
            if ($result) {
                return redirect()->back()->with('success', 'Ad created successfully..');
            }
        }
        return view('vendor/ad/new', $data);
    }

    public function adPending()
    {
        $AM = new AdModel($this->db);
        $result = $AM->listAd(0);
        $data['results'] = (object)$result;
        $data['type'] = 'Pending';
        return view('vendor/ad/list', $data);
    }

    public function adApproved()
    {

        $AM = new AdModel($this->db);
        $result = $AM->listAd(1);
        $data['results'] = (object)$result;
        $data['type'] = 'Approved';
        return view('vendor/ad/list', $data);
    }

    public function profile()
    {
        $id = $this->session->get('member_id');
        $userData = (object)$this->VM->find($id);
        $data = [
            'user' => $userData,
        ];
        return view('vendor/account/profile', $data);
    }

    public function profileEdit()
    {
        $id = $this->session->get('member_id');
        $userData = (object)$this->VM->find($id);
        $data = [
            'user' => $userData,
        ];

        if (isset($_POST['update_profile'])) {
            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['mobile'],
            ];

            $result = $this->VM->update($id, $data);
            if ($result) {
                return redirect()->back()->with('success', 'Profile updated successfully..');
            }
        }

        return view('vendor/account/profile-edit', $data);
    }

    public function changePassword()
    {
        $id = $this->session->get('member_id');
        $userData = (object)$this->VM->find($id);
        $data = [
            'user' => $userData,
        ];

        if (isset($_POST['password_btn'])) {
            $old_pass = $_POST['old_password'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $rules = [
                'old_password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Old password is required.',
                    ],
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'New password is required.',
                    ],
                ],
                'confirm_password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Confirm password is required.',
                    ],
                    'rules' => 'matches[password]',
                    'errors' => [
                        'matches' => 'Confirm password should match password.',
                    ],
                ],
            ];

            if ($this->validate($rules)) {
                $pro = (object)$this->VM->find($id);
                if ($pro->password != $old_pass) {
                    return redirect()->back()->with('error', 'Wrong old password, Please enter correct password');
                }

                $data = [
                    'password' => $password,
                ];

                $result = $this->VM->update($id, $data);
                if ($result) {
                    return redirect()->back()->with('success', 'Password updated successfully..');
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('vendor/account/change-password', $data);
    }
}
