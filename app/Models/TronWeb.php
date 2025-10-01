<?php

namespace App\Models;

use CodeIgniter\Model;
use kornrunner\Ethereum\Address;
use Config\Database;

class TronWeb extends Model
{

    public function createAddress()
    {
        $address = new Address();

        return (object)[
            'address_hex' => $address->get(),
            'address_base58' => $address->get(),
            'private_key' => $address->getPrivateKey(),
            'public_key' => $address->getPublicKey(),
        ];
    }

    public function usdtTxn($address)
    {
        
        $db      = Database::connect();

        $contractAddress = "0x55d398326f99059fF775485246999027B3197955";

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.etherscan.io/v2/api?chainid=56&module=account&action=tokentx&contractaddress=$contractAddress&address=$address&page=1&offset=5&startblock=0&endblock=999999999&sort=asc&apikey=YRG9WQ75KEUYAJRR83QCQF2XZKGAFJNRRP",
            

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
        
        $response = json_decode($response);
        if(isset($response->result)){
            foreach ($response->result as $result) {
                if ($result->to == $address) {
                    $amount = $result->value / 1000000000000000000;
                    $data = [
                        'transaction_id' => $result->hash,
                        'block_timestamp' => $result->timeStamp,
                        'from' => $result->from,
                        'to' => $result->to,
                        'type' => 'Transfer',
                        'value' => $amount,
                    ];
                    $count = $db->query("SELECT COUNT(*) as total FROM add_fund_txn WHERE transaction_id='$result->hash'")->getRow()->total;
                    if (!$count) {
                        if ($db->table('add_fund_txn')->insert($data)) {
                            $db->query("UPDATE members SET wallet=wallet+$amount WHERE wallet_address='$address'");
                        }
                    }
                }
            }
        }
        return;
        exit;
        $contract = $this->tron->contract('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');  // Tether USDT https://tronscan.org/#/token20/TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t
        $results = $contract->getTransactions($address);
        $data = [];
    }

    /* public function impsRequest($account_holder, $account_no, $bank, $ifsc, $mid, $amount)
    {
        $date = date('Y-m-d');
        $TxnDetail = new TxnDetails();

        $TxnDetail->insert([
            'member_id' => $mid,
            'amount' => $amount,
            'type' => 'Withdrawal',
            'account_holder' => $account_holder,
            'account_no' => $account_no,
            'bank' => $bank,
            'ifsc' => $ifsc,
            'date_created' => $date
        ]);
    } */

    public function sendTokenk24($toaddress, $mid, $amount)
    {
        $WALLET = "TTC7bJJ6HT7XJ9YnU8tewp4yE4gGVbqCED";
        $PKRY = "465030c32a4af51275fe85aecf6f73aa26f82819e50a6b07a3c3e6209579c846";
        $CONTRACT = "TRqqtP1zifQBadaFJDzCxUggRmXZhGoYr8"; //K24 contract address

        $this->tron->setAddress($WALLET);
        $this->tron->setPrivateKey($PKRY);

        $TOKEN = $this->tron->contract($CONTRACT);

        $transfer_amount = $amount - $amount * .1;

        $res = $TOKEN->transfer($toaddress, $transfer_amount, $WALLET);
        if ($res['txid']) {
            $date = date('Y-m-d');
            $TxnDetail = new TxnDetails();
            $TxnDetail->insert([
                'member_id' => $mid,
                'amount' => $amount,
                'type' => 'WithdrawalToken',
                'hash' => $res['txid'],
                'upgrade_id' => $toaddress,
                'date_created' => $date
            ]);
        }

        return $res;
    }

    public function SendTokenByAdmin($id, $toaddress, $amount){
        $WALLET_TRON = env('wallet.ADDRESS');
        $PKRY_TRON = env('wallet.KEY');

        $res = $this->transferToken($WALLET_TRON, $toaddress, $PKRY_TRON, $amount);
        $hash_code = $res['txn_no'];
        $db = Database::connect();
        $db->query("UPDATE txn_details SET hash='$hash_code', status=1 WHERE id=$id");
    }


    public function sendToken($toaddress, $mid, $amount, $transfer_from)
    {
        /* $WALLET = "0x33868dE1a86f16218D604445165d7Ac0890F85cD";
        $PKRY = "15f8092adb7b7f2d535994998ccf31e986fe2977e9242d595d6afeaf8e776e07"; */
        $per=.05;
        if($transfer_from=='wallet_fund'){
            $per=.05;
        }
        $transfer_amount = $amount - $amount * $per;


        //$res = $this->transferToken($WALLET, $toaddress, $PKRY, $transfer_amount);

        $date = date('Y-m-d');
        $TxnDetail = new TxnDetails();
        $TxnDetail->insert([
            'member_id' => $mid,
            'amount' => $amount,
            'transfer_amount' => $transfer_amount,
            'type' => 'Withdrawal',
            'transfer_from' => $transfer_from,
            'hash' => null,
            'upgrade_id' => $toaddress,
            'date_created' => $date,
            'status'=>0,
        ]);

        /* if($transfer_from=='wallet_fund'){
            $this->db->query("UPDATE members SET wallet=wallet-$amount WHERE member_id='$mid'");
        } */
        return true;
    }
    public function transferUsdtToMainWallet()
    {

        $WALLET_TRON = env('wallet.ADDRESS');
        $PKRY_TRON = env('wallet.KEY');

        $date = date('Y-m-d H:i:s');
        $db      = Database::connect();
        $results = $db->query("SELECT * FROM add_fund_txn WHERE is_transfer=0")->getResult();
        foreach ($results as $result) {
            $wallet_address = $result->to;

            $user_wallet = $db->query("SELECT * FROM members WHERE wallet_address='$wallet_address'")->getRow();


            $user_address = $user_wallet->wallet_address;
            $user_key = $user_wallet->private_key;
            $user_amount = round($result->value);

            
            $transfer_amount = 200;
            


            $res = $this->transferBNB($WALLET_TRON, $wallet_address, $PKRY_TRON, $transfer_amount);

            
            //$res =  $this->tron->send($wallet_address, $transfer_amount);
            echo "<br />SETP3";
            $db->table('usdt_transfer')->insert([
                'add_fund_id' => $result->id,
                'amount' => $transfer_amount,
                'from_wallet' => $WALLET_TRON,
                'to_wallet' => $wallet_address,
                'hash_code' => $res['txn_no'],
                'type' => 'TRX',
                'date' => $date,
            ]);
            //TRANSFER USDT
            
            $db->query("UPDATE add_fund_txn SET is_transfer=1 WHERE id=$result->id");
        }
        echo "Done";
    }

    public function transferUsdtToMainWallet2()
    {

        $to_wallet = env('wallet.DEPOSITADDRESS');

        $date = date('Y-m-d H:i:s');
        $db      = Database::connect();
        $results = $db->query("SELECT * FROM add_fund_txn WHERE is_transfer=1")->getResult();
        foreach ($results as $result) {
            $wallet_address = $result->to;

            $user_wallet = $db->query("SELECT * FROM members WHERE wallet_address='$wallet_address'")->getRow();


            $user_address = $user_wallet->wallet_address;
            $user_key = $user_wallet->private_key;
            $user_amount = round($result->value);

            
            

            $WALLET = $user_address;
            $PKRY = $user_key;
            $transfer_amount = $user_amount;

            $res = $this->transferToken($WALLET, $to_wallet, $PKRY, $transfer_amount);

           
            $hash = "###";
            $db->table('usdt_transfer')->insert([
                'add_fund_id' => $result->id,
                'amount' => $user_amount,
                'from_wallet' => $result->to,
                'to_wallet' => $to_wallet,
                'hash_code' => $res['txn_no'],
                'type' => 'USDT',
                'date' => $date,
            ]);

            //UPDATE STATUS
            echo "<br />SETP5";
            $db->query("UPDATE add_fund_txn SET is_transfer=2 WHERE id=$result->id");
        }
    }
    private function transferToken($fromAddress, $toAddress, $privateKey, $amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://185.28.22.1:3000/transfer-token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "fromAddress": "'.$fromAddress.'",
                "toAddress": "'.$toAddress.'",
                "privateKey": "'.$privateKey.'",
                "amount": "'.$amount.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }
    private function transferBNB($fromAddress, $toAddress, $privateKey, $amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://185.28.22.1:3000/transfer-bnb',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "fromAddress": "'.$fromAddress.'",
                "toAddress": "'.$toAddress.'",
                "privateKey": "'.$privateKey.'",
                "amount": "'.$amount.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }
    

    

    



    public function sendToken29_12_23($toaddress, $mid, $amount)
    {
        $WALLET = "TTC7bJJ6HT7XJ9YnU8tewp4yE4gGVbqCED";
        $PKRY = "465030c32a4af51275fe85aecf6f73aa26f82819e50a6b07a3c3e6209579c846";
        $CONTRACT = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";

        $this->tron->setAddress($WALLET);
        $this->tron->setPrivateKey($PKRY);

        $TOKEN = $this->tron->contract($CONTRACT);
        try {

            $transfer_amount = $amount - $amount * .05;

            $res = $TOKEN->transfer($toaddress, $transfer_amount, $WALLET);


            $date = date('Y-m-d');
            $TxnDetail = new TxnDetails();
            $TxnDetail->insert([
                'member_id' => $mid,
                'amount' => $amount,
                'type' => 'Withdrawal',
                'hash' => $res['txid'],
                'upgrade_id' => $toaddress,
                'date_created' => $date
            ]);
            return true;
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            echo $e->getMessage();
        }
    }
    public function sendTokenAdmin($toaddress, $wallet, $private, $mid, $amount)
    {
        $WALLET = $wallet;
        $PKRY = $private;
        $CONTRACT = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";

        $this->tron->setAddress($WALLET);
        $this->tron->setPrivateKey($PKRY);

        $TOKEN = $this->tron->contract($CONTRACT);
        try {
            $transfer_amount = $amount;

            $res = $TOKEN->transfer($toaddress, $transfer_amount, $WALLET);


            $date = date('Y-m-d');
            $TxnDetail = new TxnDetails();
            $TxnDetail->insert([
                'member_id' => $mid,
                'amount' => $amount,
                'type' => 'Admin Withdrawal',
                'hash' => $res['txid'],
                'upgrade_id' => $toaddress,
                'date_created' => $date
            ]);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            echo $e->getMessage();
        }
    }
}
