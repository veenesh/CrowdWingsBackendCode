<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\SubscriptionModel;
use App\Models\MemberModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\EmailModel;
use App\Models\TronWeb;
use IEXBase\TronAPI\Tron;


class User extends BaseController
{

  public function __construct()
  {
    $this->session = session();
  }

  public function login()
  {
    $MM = new MemberModel($this->db);
    if (isset($_POST['login'])) {
      $result = $MM->validateMember($_POST);
      if ($result->is_valid) {
        $this->session->set('login_member_id', $result->valid_id);
        return redirect()->to('member/dashboard');
      }
    }
    return view('auth/user-login');
  }
     public function forgotPassword()
  {
    $MM = new MemberModel($this->db);
    if (isset($_POST['login'])) {
      $result = (object)$MM->where('member_id', $_POST['member_id'])->first();
  
      if (isset($result->member_id)) {
        $EMAIL = new EmailModel();

          $name = $result->member_id;
          $to = $result->email;
          $subject = "Kasper24 Password";
          $body = '<p>
          Hello '.$name.'<br />
          
          Your password for Kasper24 login is '.$result->password.', Please login to account.</p>';
         $EMAIL->sendEmail2($name, $to, $subject, $body);
    
          
        return redirect()->to('user/login')->with('success', 'You password send to your email');
      }
      return redirect()->back()->with('error', 'Please enter registered member id');
    }
    return view('auth/forgot');
  }

  public function confirm(){
    $MM = new MemberModel();
    $status = $MM->emailConfirmation($_GET);
    if($status==1){
      $title = 'Thanks for confirming email';
      $message = '20 Token credited to your account';
    }
    else if($status==0){
      $title = 'You already confirm your email';
      $message = '20 Token already credited to your account';
    }
    else{
      $title = 'Somthing is wrong';
      $message = 'Please check your email and click on link';
    }
    $data = [
      'title'=>$title,
      'message'=>$message,
    ];
    return view('auth/confirm', $data);
  }

  public function thankyou()
  {

    if (!isset($_SESSION['thankyou_username'])) {
      return redirect()->to('/user/login');
    }

    $username = $this->session->get('thankyou_username');
    $password = $this->session->get('thankyou_password');
    $name = $this->session->get('thankyou_name');

    $data['name'] = $name;
    $data['username'] = $username;
    $data['password'] = $password;

    $this->session->destroy();

    return view('auth/user-thankyou', $data);
  }

 
  public function register()
  {

    $TRONWEB = new TronWeb();

    
    
    $data = [];



    $MM = new MemberModel($this->db);
    $SM = new StateModel($this->db);
    $CM = new CityModel($this->db);

    if (isset($_GET['ref'])) {
      $res = $MM->WHERE(['username' => $_GET['ref']])->find();
      if (sizeof($res)) {
        $res = $res[0];
        $_POST['sponsor_id'] = $res['username'];
        $_POST['sponsor_name'] = $res['name'];
      }
    }

    $data['states'] = $SM->findAll();
    $data['cities'] = [];
    if (isset($_POST['state'])) {
      $data['cities'] = $CM->where('state_id', $_POST['state'])->findAll();;
    }

    if (isset($_POST['register'])) {

      $rules = [

        'sponsor_id' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Sponsor is required.',
          ],
        ],
        'position' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Position is required.',
          ],
        ],
        'name' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Name is required.',
          ],
        ],
        'email' => [
          'rules' => 'required|valid_email|is_unique[members.email]',
          'errors' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please enter a valid email.',
            'is_unique'=>'Email already registered with us',
          ],
        ],
        'phone' => [
          'rules' => 'required|integer|max_length[10]|min_length[10]',
          'errors' => [
            'required' => 'Phone is required.',
            'integer' => 'Please enter 10 digit phone number.',
            'max_length' => 'Only 10 digit phone number.',
            'min_length' => 'Only 10 digit phone number.',
          ],
        ],

        'password' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Password is required.',
          ],
        ],

      ];

      if ($this->validate($rules)) {
        $sponsor_id = $_POST['sponsor_id'];
        //$is_user = (object)$MM->find($sponsor_id);

        $is_user = (object)$MM->where('member_id', $sponsor_id)->find()[0];


        if (!isset($is_user->member_id)) {

          return redirect()->back()->withInput()->with('error', 'Invalid sponsor id, Please try again...');
        }
        $username = mt_rand(100000001, 999999999);

        
        $address = $TRONWEB->createAddress();
  

        $date = date('Y-m-d');
        $dataIns = [
          'sponsor_id' => $is_user->member_id,
          'member_id' => $username,
          'username' => $username,
          'wallet_address' => $address->address_base58,
          'address_hex' => $address->address_hex,
          'private_key' => $address->private_key,
          'public_key' => $address->public_key,
          'name' => $_POST['name'],
          'email' => $_POST['email'],
          'phone' => $_POST['phone'],
          'password' => $_POST['password'],
          'created_at' => $date,
          'updated_at' => $date,
        ];

        $result = $MM->insert($dataIns);
        if ($result) {

          $MM->myTeam($username);
          $MM->myTeamByLevel($username);

          $this->session->set('thankyou_username', $username);
          $this->session->set('thankyou_password', $_POST['password']);
          $this->session->set('thankyou_name', $_POST['name']);

          $EMAIL = new EmailModel();

          $name = $_POST['name'];
          $to = $_POST['email'];
          $subject = "Registration successfull";
          $body = '<table>
          <tr>
          <td>Member ID</td>
          <td>'.$username.'</td>
        </tr>    
			<tr>
				<td>Name</td>
				<td>'.$_POST['name'].'</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>'.$_POST['email'].'</td>
			</tr>
			<tr>
				<td>Sponsor ID</td>
				<td>'.$sponsor_id.'</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>'.$_POST['password'].'</td>
			</tr>
      
		</table>
    <a href="https://kasper24.us/app/index.php/user/confirm?u='.$username.'&w='.$address->address_base58.'">Verify</a>';
          $EMAIL->sendEmail($name, $to, $subject, $body);
          
          
          
          $post_url = "http://103.255.100.37/api/voice/voice_broadcast.php";

$ARR_POST_DATA = array();

$ARR_POST_DATA['username'] = 'u11153n';
$ARR_POST_DATA['token'] = "3vefHd";
$ARR_POST_DATA['announcement_id'] = "411067";
$ARR_POST_DATA['plan_id'] = 26335;
$ARR_POST_DATA['caller_id'] = ""; // optional
$ARR_POST_DATA['contact_numbers'] = $_POST['phone']; // comma(,) seperated 

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $post_url);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($ARR_POST_DATA));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($curl);

          //$this->sendMessage($username, $_POST['password'], $_POST['name'], $_POST['phone']);
          return redirect()->to('user/thankyou');
        }
      } else {
        $data['validation'] = $this->validator;
      }
    }
    return view('auth/user-register',  $data);
  }


  public function sendMessage($mem_id, $password, $name, $phone)
  {
    $url = "https://homesafeproduct.com/user/login";
    $curl = curl_init();
    echo $message = 'http://nimbusit.info/api/pushsms.php?user=t5homesafe&key=0104X2Eh50qWqDzqzKvc&sender=HSPROD&mobile=' . $phone . '&text=Welcome ' . $name . ', We are glad to have you. Username: ' . $mem_id . ' Password: ' . $password . ' Click here to ' . $url . ' Regards, Home Safe Products&entityid=1501395570000041491&templateid=1507165460074458469';

    curl_setopt_array($curl, array(
      CURLOPT_URL => $message,
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
    echo $response;
  }

  public function logout()
  {
    $this->session->destroy();
    return redirect()->to('user/login');
  }
}
