<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EmailModel;
use App\Models\MemberModel;
use App\Models\OtpModel;
use App\Models\TeamModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\RESTful\ResourceController;
use DateInterval;
use DateTime;
use App\Models\TronWeb;

class Auth extends ResourceController
{
    //use ResponseTrait;

    public function index()
    {
        /* $json = $this->request->getJSON(true); // true = return array

        if (!$json) {
            return $this->fail('Invalid JSON input');
        }

        $userModel = new MemberModel();
        $member_id = $json['member_id'] ?? null;
        $password  = $json['password'] ?? null; */
        $userModel = new MemberModel();
        $member_id = $this->request->getVar('form')->member_id;
        $password = $this->request->getVar('form')->password;
        $user = $userModel->where('member_id', $member_id)->first();
        if (is_null($user)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid username or password',
            ];
            return $this->respond($response, 200);
        }
        if ($user['status']==2) {
            $response = [
                'status' => 'error',
                'message' => 'Your account is blocked',
            ];
            return $this->respond($response, 200);
        }
        if ($password != $user['password']) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid username or password',
            ];
            return $this->respond($response, 200);
        }
        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600 * 24 * 7;
        $payload = array(
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $user['email'],
            "id" => $user['id'],
            "member_id" => $user['member_id'],
            "name" => $user['name'],
        );
        $token = JWT::encode($payload, $key, 'HS256');
        $message = 'Please verify your email OTP send to your emaiil ID';
        if ($user['status'] == 1) {
            $message = 'Login Successful..';
        }
        $response = [
            'status' => 'success',
            'member_id' => $member_id,
            'is_active' => $user['status'],
            'message' => $message,
            'token' => $token
        ];
        return $this->respond($response, 200);
    }
    public function forgotPassword()
    {
        $userModel = new MemberModel();
        $email = $this->request->getVar()->email;

        $user = (object)$userModel->where('member_id', $email)->first();
        if (is_null($user)) {
            $response = [
                'status' => 'error',
                'message' => 'Member id not registered with us.',
            ];
            return $this->respond($response, 200);
        }

        $EMAIL = new EmailModel();

        $name = $user->name;
        $to = $user->email;
        $subject = "Your login details";
        $body = '<table>
       <tr>
        <td>Member ID</td>
        <td>' . $user->member_id . '</td>
        </tr>    
        <tr>
            <td>Password</td>
            <td>' . $user->password . '</td>
        </tr>
        </table>';
        $EMAIL->sendEmail2($name, $to, $subject, $body);
            $data['status'] = 'success';
            $data['message'] = 'Login details send to your email id';
       
        
        return $this->respond($data, 200);
    }
    public function SponsorName()
    {
        $MM = new MemberModel();
        $sponsor_id =  $this->request->getVar()->sponsor_id;
        $sponsorD = $MM->where('member_id', $sponsor_id)->find();
        $name = 'Invalid Sponsor';
        if (isset($sponsorD[0]['name'])) {
            $name = $sponsorD[0]['name'];
        }
        $data['status'] = 'success';
        $data['name'] = $name;
        return $this->respond($data, 200);
    }
    public function register2()
    {

        $TRONWEB = new TronWeb();
        $data = [];
        $MM = new MemberModel();

        $sponsor_id =  $this->request->getVar()->sponsor_id;
        $name =  $this->request->getVar()->name;
        $email =  $this->request->getVar()->email;
        $phone =  $this->request->getVar()->phone;
        $country_code =  $this->request->getVar()->country_code;
        $password =  $this->request->getVar()->password;

        $is_email = $MM->where('email', $email)->find();

        if (isset($is_email[0]['email'])) {
            $data['status'] = 'fail';
            $data['message'] = 'Email ID alreay exist';
            return $this->respond($data, 200);
        }

        $is_user = $MM->where('member_id', $sponsor_id)->find();


        if (!isset($is_user[0]['email'])) {
            $data['status'] = 'fail';
            $data['message'] = 'Invalid sponsor id, Please try again...';
            return $this->respond($data, 200);
        }


        $username = mt_rand(100001, 999999);

        $address = $TRONWEB->createAddress();


        $date = date('Y-m-d');
        $dataIns = [
            'sponsor_id' => $sponsor_id,
            'member_id' => $username,
            'username' => $username,
            'wallet_address' => $address->address_base58,
            'address_hex' => $address->address_hex,
            'private_key' => $address->private_key,
            'public_key' => $address->public_key,
            'name' => $name,
            'email' => $email,
            'country_code' => $country_code,
            'phone' => $phone,
            'password' => $password,
            'created_at' => $date,
            'updated_at' => $date,
        ];

        $result = $MM->insert($dataIns);

        if ($result) {
            $TEAM = new TeamModel();
            $TEAM->myTeam($username);
            $otp = $this->sendOTP($username, 'Register');

            $EMAIL = new EmailModel();

            $name = $name;
            $to = $email;
            $subject = "Registration successfull";
            $body = '<table>
          <tr>
          <td>Member ID</td>
          <td>' . $username . '</td>
        </tr>    
			<tr>
				<td>Name</td>
				<td>' . $name . '</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>' . $email . '</td>
			</tr>
			<tr>
				<td>Sponsor ID</td>
				<td>' . $sponsor_id . '</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>' . $password . '</td>
			</tr>
            <tr>
				<td>OTP</td>
				<td>' . $otp . '</td>
			</tr>
		</table>';
            $EMAIL->sendEmail($name, $to, $subject, $body);
            $data['status'] = 'success';
            $data['message'] = 'OTP Send to your email id';
            $data['member_id'] = $username;
        }

        return $this->respond($data, 200);
    }

    public function registerAuto()
    {
        $TRONWEB = new TronWeb();
        $data = [];
        $MM = new MemberModel();
        $total = $_GET['total'];
        for($i=1; $i<=$total; $i++){
            $sponsor_id =  $_GET['sponsor_id'];
        $position =  $_GET['position'];
        $name =  'User_' . uniqid();
        $email =  $name."@gmail.com";
        $phone =  '9898989898';
        $country_code =  91;
        $password =  123456;

        $is_user = $MM->where('member_id', $sponsor_id)->first();

        $username = mt_rand(100001, 999999);
        
        $address = $TRONWEB->createAddress();
        $sponsor_data = $MM->findNewSponsor($sponsor_id, $position);
        
        $upline_id = $sponsor_data['id'];
        $new_position = $sponsor_data['position'];
        $date = date('Y-m-d');
        $dataIns = [
            'sponsor_id' => $sponsor_id,
            'upline' => $upline_id,
            'position' => $new_position,
            'member_id' => $username,
            'username' => $username,
            'wallet_address' => '0x'.$address->address_base58,
            'address_hex' => $address->address_hex,
            'private_key' => $address->private_key,
            'public_key' => $address->public_key,
            'name' => $name,
            'email' => $email,
            'country_code' => $country_code,
            'phone' => $phone,
            'password' => $password,
            'created_at' => $date,
            'status' => 1,
        ];
        
        $result = $MM->insert($dataIns);
        }
        

        
    }

    public function register()
    {
        
        $TRONWEB = new TronWeb();
        $data = [];
        $MM = new MemberModel();

        $sponsor_id =  $this->request->getVar()->sponsor_id;
        $position =  $this->request->getVar()->position;
        $name =  $this->request->getVar()->name;
        $email =  $this->request->getVar()->email;
        $phone =  $this->request->getVar()->phone;
        $country_code =  $this->request->getVar()->country_code;
        $password =  $this->request->getVar()->password;
        
        /*$is_email = $MM->where('email', $email)->first();

        if (isset($is_email['email'])) {
            $data['status'] = 'fail';
            $data['message'] = 'Email ID alreay exist';
            return $this->respond($data, 200);
        }
        */
        $is_user = $MM->where('member_id', $sponsor_id)->first();
        

        if (!isset($is_user['email'])) {
            $data['status'] = 'fail';
            $data['message'] = 'Invalid sponsor id, Please try again...';
            return $this->respond($data, 200);
        }
        /*$is_phone = $MM->where('phone', $phone)->first();

        if (isset($is_phone['phone'])) {
            $data['status'] = 'fail';
            $data['message'] = 'Phone alreay exist';
            return $this->respond($data, 200);
        }*/
        $username = mt_rand(100001, 999999);
        
        $address = $TRONWEB->createAddress();
        $sponsor_data = $MM->findNewSponsor($sponsor_id, $position);
        
        $upline_id = $sponsor_data['id'];
          $new_position = $sponsor_data['position'];

        $date = date('Y-m-d');
        $dataIns = [
            'sponsor_id' => $sponsor_id,
            'upline' => $upline_id,
            'position' => $new_position,
            'member_id' => $username,
            'username' => $username,
            'wallet_address' => '0x'.$address->address_base58,
            'address_hex' => $address->address_hex,
            'private_key' => $address->private_key,
            'public_key' => $address->public_key,
            'name' => $name,
            'email' => $email,
            'country_code' => $country_code,
            'phone' => $phone,
            'password' => $password,
            'created_at' => $date,
        ];
        
        $result = $MM->insert($dataIns);
        
        if ($result) {
            $otp = $this->sendOTP($username, 'Register');
            $MM->updateTree($upline_id, $position, $username);
            $EMAIL = new EmailModel();

            $name = $name;
            $to = $email;
            $subject = "Registration successfull";
            $body = '<table>
          <tr>
          <td>Member ID</td>
          <td>' . $username . '</td>
        </tr>    
			<tr>
				<td>Name</td>
				<td>' . $name . '</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>' . $email . '</td>
			</tr>
			<tr>
				<td>Sponsor ID</td>
				<td>' . $sponsor_id . '</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>' . $password . '</td>
			</tr>
            <tr>
				<td>OTP</td>
				<td>' . $otp . '</td>
			</tr>
		</table>';
            $EMAIL->sendEmail($name, $to, $subject, $body);
            $data['status'] = 'success';
            $data['message'] = 'OTP Send to your email id';
            $data['member_id'] = $username;

           /*  $data['data'] = [
                'username'=>$username,
                'password'=>$password,
                'link'=>'app.apk',
            ]; */
        }

        return $this->respond($data, 200);
    }
    public function sendNewOtp()
    {
        $member_id =  $this->request->getVar()->member_id;
        $type =  $this->request->getVar()->type;
        $this->sendOTP($member_id, $type);
        $data['status'] = 'success';
        $data['message'] = 'OTP Send successfully to your email id';
        return $this->respond($data, 200);
    }

    public function sendOTP($mid, $type)
    {
        $MEM = new MemberModel();
        $memD = $MEM->getData($mid);
        $OTP = new OtpModel();

        $otp = mt_rand(1001, 9999);
        $dateOtp = date('Y-m-d H:i:s');

        $existingDate = new DateTime($dateOtp);

        $interval = new DateInterval('PT30M'); // PT30M represents 30 minutes

        $existingDate->add($interval);

        $newDate = $existingDate->format('Y-m-d H:i:s');

        $dataOtp = [
            'member_id' => $memD->member_id,
            'otp' => $otp,
            'otp_for' => $type,
            'created_on' => $dateOtp,
            'valid_till' => $newDate,
        ];

        $OTP->insert($dataOtp);

        $subject = "OTP";
        $body = "";

        if ($type == 'Withdrawal') {
            $subject = "OTP for withdrawal";
            $body = "<p>OTP for withdrawal fund is : " . $otp . "</p>";
        }

        else {
            $body = "<p>OTP is : " . $otp . "</p>";
            //return $otp;
        }

        $EMAIL = new EmailModel();
        $EMAIL->sendEmail2($memD->name, $memD->email, $subject, $body);

        return $otp;
    }

    public function validateOTP()
    {
        
        $otp =  $this->request->getVar()->otp;
        $member_id = $this->request->getVar()->member_id;
        $type = $this->request->getVar()->type;

        $OTP = new OtpModel();

        $result = $OTP->validateOTP($member_id, $otp, $type);

        return $this->respond($result, 200);
    }




    public function tokenValid()
    {
        return true;
    }
}
