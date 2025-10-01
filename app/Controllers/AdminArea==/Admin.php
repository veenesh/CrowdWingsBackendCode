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

class Admin extends BaseController
{

    public function __construct()
    {
        $this->session = session();
    }
    public function index()
    {
    }
    public function dashboard()
    {

        return view('admin/dashboard');
    }

    public function chat(){
        $data=[];
        return view('admin/member/help', $data);
    }
    public function withdrawalList()
    {
        $title = 'Withdrawal List';
        $status = '';



        $data['title'] = $title;
        $MM = new MemberModel();
        $results = $MM->withdrawalList();
        
 
        $data['results'] = $results;
        return view('admin/member/withdrawal', $data);
    }
    public function memberListAll()
    {
        $title = 'All Members';
        $status = '';



        $data['title'] = $title;
        $MM = new MemberModel();
        $results = $MM->findAllAdmin();
        
        /* if(isset($_GET['type'])){
            $type=$_GET['type'];

            if($type=='all'){$title='All Members'; $status=''; $results = $MM->findAllMembers();}
            if($type=='pending'){$title='Pending Members'; $status=''; $results = $MM->findPendingMembers();}
            if($type=='active'){$title='Ative Members'; $status=''; $results = $MM->findActiveMembers();}
        } */

        $data['results'] = $results;
        return view('admin/member/list', $data);
    }

    public function memberEdit()
    {
        if (!isset($_GET['id'])) {
            return redirect()->to('admin/member/list');
        }
        $id = $_GET['id'];
        $MM = new MemberModel();

        $user = $MM->find($id);
        //echo "<pre>";
        //print_r($user);
        //exit;
        $UP = new Upgrade();
        $result = $UP->income($user['member_id']);
        $wallet_address = $user['wallet_address'];
        $private_key = $user['private_key'];

        if(isset($_POST['withdrawal']))
        {
            $TRONWEB = new TronWeb();
            $amount = $_POST['amount'];
            $to_address = $_POST['wallet'];
            $TRONWEB->sendTokenAdmin($to_address, $wallet_address, $private_key, $user['member_id'], $amount);
            return redirect()->back()->with('success', 'Amount transfered successfully');
        }
        if(isset($_POST['update_email']))
        {
            $data = [
                'email' => $_POST['email'],
            ];

            $result = $MM->update($id, $data);
            return redirect()->back()->with('success', 'Email updated successfully...');
        }
        if(isset($_POST['activate']))
        {
            $data = [
                'status' => 1,
            ];

            $result = $MM->update($id, $data);
            return redirect()->back()->with('success', 'Profile Activated');
        }
        
        if(isset($_POST['deactivate']))
        {
            $data = [
                'status' => 0,
            ];

            $result = $MM->update($id, $data);
            return redirect()->back()->with('success', 'Profile Deactivated');
        }
        $walletB = $this->walletBalance($wallet_address);
        
        $tron = $walletB->tron;
        $usdt = $walletB->usdt;

        $data = [
            'user' => $user,
            'income' => $result,
            'usdt' => $usdt,
            'tron' => $tron,
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
