<?php

namespace App\Controllers\Api;

use App\Models\AddTxnFundModel;
use App\Models\IncomeModel;
use App\Models\MemberModel;
use App\Models\OtpModel;
use App\Models\TronWeb;
use App\Models\Upgrade;
use CodeIgniter\RESTful\ResourceController;
use DateInterval;
use DateTime;

class Income extends ResourceController
{
    public function index()
    {
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->income($member_id);
        $data['result']=$result;

        return $this->respond($data, 200);
    }
    public function roiCronJob(){
        $UP = new Upgrade(); 
        $UP->saveDailyROICron();
    }

    public function matchingFullCronJob(){
        $UP = new Upgrade(); 
     
        $UP->fullMatching();
    }

    public function matchingDCronJob(){
        $UP = new Upgrade(); 
        $UP->dayMatching();
    }

    public function matchingNCronJob(){
        $UP = new Upgrade(); 
        $UP->nightMatching();
    }
    

    public function matching(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->matchingIncome($member_id);
        return $this->respond($result, 200);
    }
    
    public function leftrightbusiness(){
        $member_id = $_GET['mid'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->leftrightbusiness($member_id);
        return $this->respond($result, 200);
    }
    
    public function directData(){
        //return $this->respond('Ok', 200);
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->directIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function levelData(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->levelIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function roiData(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->roiIncomeData($member_id);
        return $this->respond($result, 200);
    }


    public function salaryData(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->salaryIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function autoPoolIncome(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = [];
        return $this->respond($result, 200);
    }

    public function rewardData(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->rewardIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function royality(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->royalityIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function binaryData(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $UP = new Upgrade(); 
        $result = $UP->binaryIncomeData($member_id);
        return $this->respond($result, 200);
    }

    public function upgrade(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $amount =  $this->request->getVar()->amount;
        $type =  $this->request->getVar()->type;
        $UP = new Upgrade(); 
        $result = $UP->upgrade($member_id, $amount, $type);
        return $this->respond($result, 200);
    }

    public function listWithdrawals(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        
        $MEM = new MemberModel();
        $result = $MEM->listWithdrawals($member_id);
        return $this->respond($result, 200);
    }
    
    public function memberDeposites(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        
        $MEM = new MemberModel();
        $result = $MEM->memberDeposites($member_id);
        return $this->respond($result, 200);
    }
    
    
    public function listUpgrades(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        
        $MEM = new MemberModel();
        $result = $MEM->listUpgrades($member_id);
        return $this->respond($result, 200);
    }
    
    
    public function transfer(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $amount =  $this->request->getVar()->amount;
        $transfer_to =  $this->request->getVar()->member_id;
        $otp =  $this->request->getVar()->otp;

        
        
        $UP = new Upgrade(); 
        $result = $UP->transfer($member_id, $transfer_to,  $amount, $otp);
        return $this->respond($result, 200);
    }

    public function withdrawalTest(){
        $TRON = new TronWeb();
        $TRON->sendToken('0xa9042365969899baA4197574A8c87B2e3A5AC366', '10001', '1');
    }

    public function withdrawal(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $transfer_to =  $this->request->getVar()->transfer_to;
        $amount =  $this->request->getVar()->amount;
        $transfer_member_id =  $this->request->getVar()->member;
        $wallet_address =  $this->request->getVar()->wallet_address;
        $transfer_from =  $this->request->getVar()->transfer_from;
        
        $otp =  $this->request->getVar()->otp;
        $UP = new Upgrade(); 
        $result = $UP->withdrawal($transfer_to, $member_id, $transfer_member_id, $amount, $wallet_address, $otp, $transfer_from);
        return $this->respond($result, 200);
    }


    public function addFund(){
        
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        
        $MEM = new MemberModel();
        $res = (object)$MEM->find($id);
        
        $TRONWEB = new TronWeb();
        
        $TRONWEB->usdtTxn($res->wallet_address);
        
        return;
        $result = [
            'status'=>'success',
        ];
        return $this->respond($result, 200);
    }

    public function addedFund(){
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        
        $MEM = new MemberModel();
        $res = (object)$MEM->find($id);

        $ADDEDFUND = new AddTxnFundModel();

        $result = $ADDEDFUND->select("*, DATE_FORMAT(FROM_UNIXTIME(block_timestamp), '%Y-%m-%d %H:%i:%s') AS date")->where('to', $res->wallet_address)->findAll();
        return $this->respond($result, 200);
    }


    

    private function sendOTP($username, $type)
    {
        $OTP = new OtpModel();
       
        $otp = mt_rand(1001, 9999);
        $dateOtp = date('Y-m-d H:i:s');

        $existingDate = new DateTime($dateOtp);

        $interval = new DateInterval('PT30M'); // PT30M represents 30 minutes

        $existingDate->add($interval);

        $newDate = $existingDate->format('Y-m-d H:i:s');

        $dataOtp = [
            'member_id' => $username,
            'otp' => $otp,
            'otp_for' => $type,
            'created_on' => $dateOtp,
            'valid_till' => $newDate,
        ];

        $OTP->insert($dataOtp);
        return $otp;
    }
    
    public function autoTransfer(){
        $TRONWEB = new TronWeb();

        $TRONWEB->transferUsdtToMainWallet();
        echo "Done";

    }

    public function autoTransfer2(){
        $TRONWEB = new TronWeb();

        $TRONWEB->transferUsdtToMainWallet2();
        echo "Done";

    }

}
