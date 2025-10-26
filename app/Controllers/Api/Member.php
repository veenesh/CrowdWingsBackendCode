<?php

namespace App\Controllers\Api;

use App\Models\MemberModel;
use App\Models\Message;
use App\Models\NewsModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Member extends ResourceController
{
    //use ResponseTrait;
    public function index()
    {
        $MEMBER = new MemberModel();
        $DB = Database::connect();
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $result = $MEMBER->getData($member_id);
        $direct = $MEMBER->directCount($member_id);
        
        $showTimer=1;
        if($direct>=2){
            $showTimer=0;
        }

        //$result = $MEMBER->select('id, member_id, country_code, phone,  wallet_address, wallet, sponsor_id,  name, image, email, status')->find($id);
        $data['status'] = 'success';
        $data['result'] = $result;
        $data['showTimer'] = $showTimer;
        return $this->respond($data, 200);
    }

    /* public function updateProfile(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $user_wallet =  $this->request->getVar()->user_wallet;        
        $otp =  $this->request->getVar()->otp;
        $MEMBER = new MemberModel();
        $result = $MEMBER->updateProfile($member_id, $user_wallet, $otp);
        //$UP = new Upgrade(); 
        //$result = $UP->withdrawal($transfer_to, $member_id, $transfer_member_id, $amount, $wallet_address, $otp);
        return $this->respond($result, 200);
    } */

    public function updateProfile()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];

        $user_wallet = $this->request->getVar('user_wallet');
        $otp         = $this->request->getVar('otp');

        // ✅ Handle image upload
        $imageFile = $this->request->getFile('image');
        $imageName = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Create unique filename
            $imageName = $imageFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/';

            // Move to /public/uploads/
            $imageFile->move($uploadPath, $imageName);
        }

        $MEMBER = new MemberModel();

        // ✅ Pass image name to model (if uploaded)
        $result = $MEMBER->updateProfile($member_id, $user_wallet, $otp, $imageName);

        return $this->respond($result, 200);
    }


    public function memberData()
    {
        $member_id = $_GET['member_id'];
        $MEMBER = new MemberModel();

        $result = $MEMBER->getData($member_id);

        $data['status'] = 'fail';
        if (isset($result->id)) {
            $data['status'] = 'success';
        }
        $data['result'] = $result;
        return $this->respond($data, 200);
    }

    public function memberDownlineByPosition()
    {
        $member_id = $_GET['member_id'];
        $position = $_GET['position'];

        $MEMBER = new MemberModel();

        $result = $MEMBER->getDwnlineDetails($member_id, $position);

        $data['status'] = 'fail';
        if (isset($result->id)) {
            $data['status'] = 'success';
        }
        $data['result'] = $result;
        return $this->respond($data, 200);
    }

    public function team()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $MEMBER = new MemberModel();
        $result = $MEMBER->team($member_id);
        return $this->respond($result, 200);
    }

    public function matchingTeam()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $MEMBER = new MemberModel();
        $result = $MEMBER->matchingTeam($member_id);
        return $this->respond($result, 200);
    }



    public function teamDirect()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $MEMBER = new MemberModel();
        $result = $MEMBER->team($member_id);
        return $this->respond($result, 200);
    }

    public function changePassword()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];

        $old_password =  $this->request->getVar()->old_password;
        $new_password =  $this->request->getVar()->new_password;

        $MEMBER = new MemberModel();
        $result = $MEMBER->changePassword($member_id, $old_password, $new_password);
        return $this->respond($result, 200);
    }

    public function teamDetails()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $level =  $this->request->getVar()->level;
        //return $this->respond($level, 200);

        $MEMBER = new MemberModel();
        $result = $MEMBER->teamData($member_id, $level);
        return $this->respond($result, 200);
    }

    public function news()
    {
        $NEWS = new NewsModel();
        $result = $NEWS->findAll();
        return $this->respond($result, 200);
    }

    public function help()
    {
        $MSG = new Message();
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];

        if (isset($_GET['test'])) {
            $MSG->sendAll();
        }

        $result = $MSG->getMessage($id);
        return $this->respond($result, 200);
    }

    public function helpMesssage()
    {
        $MSG = new Message();
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $message =  $this->request->getVar()->message;
        $date = date('Y-m-d H:i:s');
        $MSG->insert([
            'message' => $message,
            'message_from' => $id,
            'message_to' => 0,
            'created_on' => $date,
        ]);

        $result = $MSG->getMessage($id);
        return $this->respond($result, 200);
    }
}
