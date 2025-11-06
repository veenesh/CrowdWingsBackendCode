<?php

namespace App\Models;

use App\Controllers\Api\Auth;
use CodeIgniter\Model;
use Config\Database;
use DateTime;

use function PHPUnit\Framework\isNull;

class Upgrade extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'upgrades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function saveDailyROICron()
    {
        $today = date('Y-m-d');
        $dayOfWeek = date('N'); // 1=Mon, 7=Sun
        // Skip Saturday & Sunday
        if ($dayOfWeek > 5) {
            return;
        }
        // Fetch all active upgrades
        $upgrades = $this->db->query("SELECT * FROM upgrades")->getResult();
        
        
        foreach ($upgrades as $up) {

            $income = $up->daily;

            $MEMMODEL = new MemberModel();
            $direct = $MEMMODEL->directCount($up->member_id);
            if($direct>=2){
                $income = .5;
            }
            // Insert ROI
            $this->db->table('roi')->insert([
                'member_id'  => $up->member_id,
                'up_id'      => $up->id,
                'up_date'    => $up->date,
                'up_amount'  => $up->upgrade_amount,
                'income'     => $income,
                'date'       => $today,
            ]);
        }
    }
    public function fullMatching()
    {
        $startFrom = "2025-09-30";
        $endOn     = date('Y-m-d');
        $DB = Database::connect();
        $DB->query("TRUNCATE TABLE `matching_income`");
        $is_record = $DB->query("SELECT * FROM matching_income ORDER BY id DESC")->getRow();
        if (isset($is_record->date)) {
            $startFrom = $is_record->date;
        }
        // Create date objects
        $startDate = new DateTime($startFrom);
        $endDate   = new DateTime($endOn);

        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $currentDate = $date->format("Y-m-d");

            if ($currentDate == date('Y-m-d')) {
                
            } else {
                // Day matching: 00:00:01 – 12:00:00
                $dayStart = $currentDate . ' 00:00:01';
                $dayEnd   = $currentDate . ' 12:00:00';
                $this->processMatching($currentDate, 'day', $dayStart, $dayEnd);

                // Night matching: 12:00:01 – 23:59:59
                $nightStart = $currentDate . ' 12:00:01';
                $nightEnd   = $currentDate . ' 23:59:59';
                $this->processMatching($currentDate, 'night', $nightStart, $nightEnd);

                echo "Processed matching for {$currentDate}<br>";
            }
        }

        echo "Full Matching Completed";
    }


    public function dayMatching()
    {
        $date = date('Y-m-d');
        //$date = 2025-10-28;
        $startTime = $date . ' 00:00:01';
        $endTime   = $date . ' 12:00:00';

        $result = $this->processMatching($date, 'day', $startTime, $endTime);
        echo "Done";
    }

    /**
     * Run Night Matching (12:00:01 to 23:59:59)
     */
    public function nightMatching()
    {
        
        $date = date('Y-m-d');
        //$date = "2025-10-28";
        $startTime = $date . ' 12:00:01';
        $endTime   = $date . ' 23:59:59';

        $result = $this->processMatching($date, 'night', $startTime, $endTime);
        echo "Done";
    }

    private function processMatching(string $date_for, string $slot, string $startTime, string $endTime)
    {
        $date = $date_for;
        $sql = "
            SELECT 
                t.member_id,
                SUM(CASE WHEN t.position = 'leftM' THEN 1 ELSE 0 END) AS left_actual,
                SUM(CASE WHEN t.position = 'rightM' THEN 1 ELSE 0 END) AS right_actual
            FROM tree_data t
            INNER JOIN upgrades u ON t.member_added = u.member_id
            WHERE u.date BETWEEN ? AND ?
            GROUP BY t.member_id
        ";
        $results = $this->db->query($sql, [$startTime, $endTime])->getResult();
       
        foreach ($results as $row) {
            $mid = $row->member_id;


            $left_actual  = (float) $row->left_actual;
            $right_actual = (float) $row->right_actual;

            // --- Carry forward ---
            $carrySql = "
                SELECT carry_L AS left_carry, carry_R AS right_carry
                FROM matching_income
                WHERE member_id = ?
                ORDER BY id DESC LIMIT 1
            ";
            $carry = $this->db->query($carrySql, [$mid])->getRow();

            $left_carry  = $carry ? (float) $carry->left_carry : 0;
            $right_carry = $carry ? (float) $carry->right_carry : 0;

            $left_with_carry  = $left_actual + $left_carry;
            $right_with_carry = $right_actual + $right_carry;

            $pair       = min($left_with_carry, $right_with_carry);
            $total_pair = $pair;

            // --- Max package ---
            $max_package = 10;

            $capping = 0;
            if ($total_pair > $max_package) {
                $capping = $total_pair - $max_package;
                $total_pair = $max_package;
            }

            // Example: income = total_pair (adjust if formula changes)
            $income = $total_pair * 2;
            $capping = $capping * 2;
            $after_capping = $income;

            // New carry values
            $new_left_carry  = $left_with_carry - ($pair);
            $new_right_carry = $right_with_carry - ($pair);
            
        
            
            // --- Insert ---
            $this->db->table('matching_income')->insert([
                'member_id'        => $mid,
                'date'             => $date,
                'slot'             => $slot,
                'leftM'            => $left_actual,
                'rightM'           => $right_actual,
                'left_with_carry'  => $left_with_carry,
                'right_with_carry' => $right_with_carry,
                'pair'             => $pair,
                'capping'          => $capping,
                'after_capping'    => $after_capping,
                'income'           => $income,
                'carry_L'          => $new_left_carry,
                'carry_R'          => $new_right_carry,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        return [
            'status'  => 'success',
            'message' => ucfirst($slot) . " matching income processed successfully",
            'date'    => $date,
            'slot'    => $slot
        ];
    }

    public function createLevelIncome($mid)
    {
        $levels = $this->db->query("WITH RECURSIVE team AS (
            SELECT id, member_id, sponsor_id, 0 AS level
            FROM members
            WHERE member_id = '$mid'
        
            UNION ALL
        
            SELECT m.id, m.member_id, m.sponsor_id, t.level + 1
            FROM members m
            INNER JOIN team t ON m.member_id = t.sponsor_id
            WHERE t.level < 21 
        )
        SELECT tt.* 
        FROM team as tt INNER JOIN upgrades as u  ON u.member_id=tt.member_id WHERE tt.level>0;")->getResult();

        $LEVELINCOME = new LevelIncome();
        $date = date('Y-m-d');
        $data = [];

        foreach ($levels as $res) {
            $income = 0;
            if ($res->level == 1) {
                $income = 5;
            } elseif ($res->level >= 2 && $res->level <= 6) {
                $income = 1;
            } elseif ($res->level >= 7 && $res->level <= 11) {
                $income = 0.5;
            } elseif ($res->level >= 12 && $res->level <= 21) {
                $income = 0.25;
            }

            $data[] = [
                'member_id'     => $res->member_id,              // who gets the income
                'type'          => 'level',
                'income_from'   => $mid,   // downline source
                'level'         => $res->level,
                'unit_upgrade'  => 100,
                'member_daily'  => 0,
                'per'           => 0,
                'income'        => $income,
                'date'          => $date,
            ];
        }

        if (count($data)) {
            $LEVELINCOME->insertBatch($data);
        }
    }


    public function addToPool($table, $mem_id, $up_id, $income)
    {
        $next_table = "";
        $next_income = 0;
        if ($table == 'pool1') {
            $next_table = 'pool2';
            $next_income = 12.4;
        }
        if ($table == 'pool2') {
            $next_table = 'pool3';
            $next_income = 76.88;
        }
        $MEMBER = new MemberModel();
        $result = $this->db->query("SELECT * FROM $table WHERE member_id='$mem_id' AND up_id=$up_id")->getRow();


        if (!isset($result->id)) {
            $result = $this->findPoolSponsor($table, 2);
            $upline_id = $result->upline;
            $uid = uniqid(30);
            $this->db->table($table)->insert([
                'user_id' => $uid,
                'member_id' => $mem_id,
                'upline_id' => $upline_id,
                'up_id' => $up_id,
            ]);

            $poolIncomes = $this->db->query("WITH RECURSIVE downline AS (
                SELECT 
                    user_id,
                    upline_id,
                    member_id,
                    0 AS level
                FROM {$table}
                WHERE user_id = '$uid'
            
                UNION ALL
            
                SELECT 
                    p.user_id,
                    p.upline_id,
                    p.member_id,
                    d.level + 1
                FROM {$table} p
                INNER JOIN downline d 
                    ON p.user_id = d.upline_id
                WHERE d.level < 5
            )
            SELECT * FROM downline d WHERE d.level>0")->getResult();
            foreach ($poolIncomes as $pi) {
                $this->db->query("UPDATE {$table} as t SET t.income = t.income + {$income},
                t.upgrade_balance = t.upgrade_balance + {$income} WHERE t.user_id='$pi->user_id'");
            }
            /*             UPDATE {$table} t
            JOIN downline d ON t.user_id = d.user_id
            SET t.income = t.income + {$income},
                t.upgrade_balance = t.upgrade_balance + {$income} WHERE d.level>0 */
            $upgrade_balance_limit = $income * 62;
            if ($next_table != '') {

                $results = $this->db->query("SELECT id, user_id, member_id, up_id
            FROM {$table}
            WHERE upgrade_balance >= {$upgrade_balance_limit} AND user_id NOT IN(SELECT user_id FROM {$next_table})")->getResult();
                foreach ($results as $rr) {
                    $this->db->query("UPDATE {$table} SET upgrade_balance=0 WHERE id=$rr->id");
                    $this->addToPool($next_table, $rr->member_id, $rr->up_id, $next_income);
                }
            }
        }
    }
    public function findPoolSponsor($table, $pool)
    {
        $sql1 = "SELECT * FROM (SELECT *, COUNT(*) as total FROM $table WHERE upline_id!='' GROUP BY upline_id) aa WHERE total<$pool";


        $sql2 = "SELECT * FROM (SELECT *, COUNT(*) as total FROM $table WHERE upline_id!='' GROUP BY upline_id) aa WHERE total<=$pool ORDER BY id DESC";

        $is_any = $this->db->query($sql1)->getRow();

        if (isset($is_any->upline_id)) {
            $pool_member = $is_any->upline_id;
        } else {
            $res = $this->db->query($sql2)->getRow();
            if (isset($res->upline_id)) {
                $last_member_id = $res->upline_id;
                $last_complete_pool_id = $this->db->query("SELECT * FROM $table WHERE user_id='$last_member_id'")->getRow()->id;
                $pool_member = $this->db->query("SELECT * FROM $table WHERE id>'$last_complete_pool_id'")->getRow()->user_id;
            } else {
                $pool_member = 1;
            }
            $reEntry = 0;
        }

        return (object)[
            'upline' => $pool_member,
        ];
    }


    public function upgrade($mid, $amount, $type)
    {
        $INCOME = $this->income($mid);
        $limitRemaining = $INCOME['limitRemaining'];
        $limit = $INCOME['limit'];
        $totalB = $INCOME['totalIncome'];
        $capping = 0;
        if ($totalB > $limit) {
            $capping = $totalB - $limit;
        }

        if ($limitRemaining != 0) {
            $data = [
                'status' => 'error',
                'message' => 'You already have an active income, Wait to complete it',
            ];
            return $data;
        }

        $walletBalance = 0;
        $resultM = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        $walletBalance = $resultM->wallet;
        if ($amount < 100) {
            $data = [
                'status' => 'error',
                'message' => 'Investment amount is 100 USD',
            ];
            return $data;
        }

        if ($walletBalance >= $amount) {
            $withdrawal_limit = $amount * 2;
            $daily = ($amount * .25) / 100;

            $date = date('Y-m-d H:i:s');
            $result = $this->insert([
                'member_id' => $mid,
                'unit_upgrade' => $amount,
                'upgrade_amount' => $amount,
                'withdrawal_limit' => $withdrawal_limit,
                'daily' => $daily,
                'date' => $date,
                'capping' => $capping,
            ]);

            $TXND = new TxnDetails();
            $TXND->insert([
                'member_id' => $mid,
                'amount' => $amount,
                'type' => $type,
                'date_created' => $date,
            ]);

            $this->db->query("UPDATE members SET wallet=wallet-$amount WHERE member_id='$mid'");
            $this->addToPool('pool1', $mid, $result, 1);

            $this->createLevelIncome($mid);

            $data = [
                'status' => 'success',
                'message' => 'Upgraded successfully',
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Do not have fund to upgrade',
            ];
        }

        return $data;
    }

    public function upgradeOLD($mid, $amount, $type)
    {

        //TYPE CAN BE investment or reinvestment;
        $walletBalance = 0;
        if ($type == 'investment') {
            $result = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
            $walletBalance = $result->wallet;
            if ($amount < 100 || $amount > 2500) {
                $data = [
                    'status' => 'error',
                    'message' => 'Min deposit is 100 and max deposit is 2500 USD',
                ];
                return $data;
            }
        } else if ($type == 'reinvestment') {
            $Income = $this->income($mid);
            $walletBalance = $Income['can_withdrawal'];

            if ($amount < 25 || $amount > 2500) {
                $data = [
                    'status' => 'error',
                    'message' => 'Min deposit is 25 and max deposit is 2500 USD',
                ];
                return $data;
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Somthing is wrong, Please try again',
            ];
        }


        if ($walletBalance >= $amount) {
            $withdrawal_limit = $amount * 3;
            $daily = ($amount * .75) / 100;
            if ($amount >= 500) {
                $daily = ($amount * 1.10) / 100;
            }
            $date = date('Y-m-d H:i:s');
            $result = $this->insert([
                'member_id' => $mid,
                'unit_upgrade' => $amount,
                'upgrade_amount' => $amount,
                'withdrawal_limit' => $withdrawal_limit,
                'daily' => $daily,
                'date' => $date,
            ]);

            $TXND = new TxnDetails();
            $TXND->insert([
                'member_id' => $mid,
                'amount' => $amount,
                'type' => $type,
                'date_created' => $date,
            ]);
            if ($type == 'investment') {
                $this->db->query("UPDATE members SET wallet=wallet-$amount WHERE member_id='$mid'");
            }


            $levels = $this->db->query("SELECT * FROM team WHERE level_member='$mid' AND level<=10")->getResult();

            foreach ($levels as $level) {
                //DIRECT INCOME
                if ($level->level == 1) {
                    $DIRECT = new DirectIncome();
                    $directPer = 10;
                    $incomeD = ($amount * $directPer) / 100;
                    $DIRECT->insert([
                        'member_id' => $level->member_id,
                        'income_from' => $mid,
                        'unit_upgrade' => $amount,
                        'upgrade_amount' => $amount,
                        'per' => $directPer,
                        'income' => $incomeD,
                        'date' => $date,
                    ]);
                }

                //LEVEL INCOME
                if ($level->level == 1) {
                    $per = 15;
                }
                if ($level->level == 2) {
                    $per = 10;
                }
                if ($level->level == 3) {
                    $per = 5;
                }
                if ($level->level == 4) {
                    $per = 5;
                }
                if ($level->level == 5) {
                    $per = 2.5;
                }
                if ($level->level == 6) {
                    $per = 2.5;
                }
                if ($level->level == 7) {
                    $per = 2;
                }
                if ($level->level == 8) {
                    $per = 1;
                }
                if ($level->level == 9) {
                    $per = 1;
                }
                if ($level->level == 10) {
                    $per = 1;
                }

                $level_daily = ($daily * $per) / 100;

                $LEVEL = new LevelIncome();
                $dataL = [
                    'member_id' => $level->member_id,
                    'income_from' => $mid,
                    'type' => 'Level',
                    'level' => $level->level,
                    'unit_upgrade' => $amount,
                    'member_daily' => $daily,
                    'per' => $per,
                    'daily' => $level_daily,
                    'date' => $date,
                ];
                $LEVEL->insert($dataL);
            }

            $data = [
                'status' => 'success',
                'message' => 'Upgraded successfully',
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Do not have fund to upgrade',
            ];
        }

        return $data;
    }


    public function transfer($mid, $transfer_to,  $amount, $otp)
    {
        if ($mid == $transfer_to) {
            $data = [
                'status' => 'error',
                'message' => 'You can not transfer fund to your own ID',
                'redirect' => 0,
            ];
            return $data;
        }
        $mem = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        $memTransfer = $this->db->query("SELECT * FROM members WHERE member_id='$transfer_to'")->getRow();

        if (!isset($memTransfer->member_id)) {
            $data = [
                'status' => 'error',
                'message' => 'Invalid Member ID',
                'redirect' => 0,
            ];
            return $data;
        }
        if ($mem->wallet < $amount) {
            $data = [
                'status' => 'error',
                'message' => 'You can transfer max $' . $mem->wallet,
                'redirect' => 0,
            ];
            return $data;
        }
        if ($otp == null) {
            $AUTH = new Auth();
            $otp = $AUTH->sendOTP($mid, 'Fund Transfer');
            $data = [
                'status' => 'success',
                'otp_sent' => 1,
                'message' => 'OTP send to your email id',
                'redirect' => 0,
            ];
            return $data;
        }
        $OTP = new OtpModel();
        $result = $OTP->validateOTP($mid, $otp, 'Withdrawal');
        if ($result['status'] == 'success') {
            $data = [
                'status' => 'error',
                'message' => 'You do not have fund to withdrawal',
                'redirect' => 1,
            ];
            return $data;
        }
        return $result;
    }

    public function withdrawal($transfer_to, $mid, $transfer_member_id, $amount, $wallet_address, $otp, $transfer_from)
    {
        $getmem = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        //echo $wallet
        $income = $this->income($mid)['can_withdrawal'];

        if ($transfer_to == 'member' or $transfer_from == 'wallet_fund') {
            if ($getmem->wallet < $amount) {
                $data = [
                    'status' => 'error',
                    'message' => 'You do not have fund in wallet',
                    'redirect' => 0,
                ];
                return $data;
            }
        } else {
            if ($income <= 0) {
                $data = [
                    'status' => 'error',
                    'message' => 'You do not have fund to transfer',
                    'redirect' => 0,
                ];
                return $data;
            }

            if ($amount > $income) {
                $data = [
                    'status' => 'error',
                    'message' => 'You can transfer max ' . $income . 'USD',
                    'redirect' => 0,
                ];
                return $data;
            }
        }

        if ($amount < 5) {
            $data = [
                'status' => 'error',
                'message' => 'Min withdrawal is 5 USD',
                'redirect' => 0,
            ];
            return $data;
        }


        if ($transfer_member_id != null) {
            $memD = $this->db->query("SELECT * FROM members WHERE member_id='$transfer_member_id'")->getRow();
            if (!isset($memD->id)) {
                $data = [
                    'status' => 'error',
                    'message' => 'Invalid member id',
                    'redirect' => 0,
                ];
                return $data;
            }
        }
        if ($otp == null) {
            $AUTH = new Auth();
            $memD = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
            $otp = $AUTH->sendOTP($memD->member_id, 'Withdrawal');
            $data = [
                'status' => 'success',
                'otp_sent' => 1,
                'message' => 'OTP send to your email id',
                'redirect' => 0,
            ];
            return $data;
        }
        $OTP = new OtpModel();
        $result = $OTP->validateOTP($mid, $otp, 'Withdrawal');
        if ($result['status'] == 'success') {
            if ($transfer_to == 'address') {
                $TRONWEB = new TronWeb();
                $TRONWEB->sendToken($wallet_address, $mid, $amount, $transfer_from);
            } else if ($transfer_to == 'member') {
                $date = date('Y-m-d');
                $TxnDetail = new TxnDetails();
                $TxnDetail->insert([
                    'member_id' => $mid,
                    'amount' => $amount,
                    'transfer_amount' => $amount,
                    'type' => 'fund transfer',
                    'hash' => $transfer_member_id,
                    'upgrade_id' => 'Transfer',
                    'date_created' => $date,
                    'status' => 1
                ]);
                $transfer_amount = $amount;
                $this->db->query("UPDATE members SET wallet=wallet-$transfer_amount WHERE member_id='$mid'");
                $this->db->query("UPDATE members SET wallet=wallet+$transfer_amount WHERE member_id='$transfer_member_id'");
            } else {
                $date = date('Y-m-d');
                $TxnDetail = new TxnDetails();
                $TxnDetail->insert([
                    'member_id' => $mid,
                    'amount' => $amount,
                    'transfer_amount' => $amount,
                    'type' => 'Withdrawal',
                    'hash' => 'Wallet Transfer',
                    'upgrade_id' => 'Transfer',
                    'date_created' => $date,
                    'status' => 1
                ]);
                $transfer_amount = $amount - $amount * .05;
                $this->db->query("UPDATE members SET wallet=wallet+$transfer_amount WHERE member_id='$mid'");
            }
            $data = [
                'status' => 'success',
                'message' => 'Amount transfered successfully..',
                'redirect' => 1,
            ];
            return $data;
        }
        return $result;
    }

    public function poolIncomeData($mid)
    {
        $pool1 = $this->db->query("SELECT * FROM pool1 WHERE member_id='$mid'")->getResult();
        $pool1D = $this->db->query("WITH RECURSIVE member_levels AS (
            SELECT 
                m.user_id,
                m.member_id,
                m.upline_id,
                0 AS level
            FROM (
                SELECT *
                FROM pool1
                WHERE member_id = '$mid'
                ORDER BY user_id ASC
                LIMIT 1
            ) AS m
        
            UNION ALL
        
            -- Recursive query: get children
            SELECT 
                c.user_id,
                c.member_id,
                c.upline_id,
                ml.level + 1 AS level
            FROM pool1 c
            INNER JOIN member_levels ml 
                ON c.upline_id = ml.user_id
        )
        SELECT 
            level,
            COUNT(*) AS total
        FROM member_levels WHERE level>0 AND level<=5
        GROUP BY level
        ORDER BY level;")->getResult();
        $pool1Total = $this->db->query("SELECT SUM(upgrade_balance) as upgrade_balance, SUM(income) as income FROM pool1 WHERE member_id='$mid'")->getRow();

        $pool2 = $this->db->query("SELECT * FROM pool2 WHERE member_id='$mid'")->getResult();

        $pool2D = $this->db->query("WITH RECURSIVE member_levels AS (
            SELECT 
                m.user_id,
                m.member_id,
                m.upline_id,
                0 AS level
            FROM (
                SELECT *
                FROM pool2
                WHERE member_id = '$mid'
                ORDER BY user_id ASC
                LIMIT 1
            ) AS m
        
            UNION ALL
        
            -- Recursive query: get children
            SELECT 
                c.user_id,
                c.member_id,
                c.upline_id,
                ml.level + 1 AS level
            FROM pool2 c
            INNER JOIN member_levels ml 
                ON c.upline_id = ml.user_id
        )
        SELECT 
            level,
            COUNT(*) AS total
        FROM member_levels WHERE level>0 AND level<=5
        GROUP BY level
        ORDER BY level;")->getResult();
        $pool2Total = $this->db->query("SELECT SUM(upgrade_balance) as upgrade_balance, SUM(income) as income FROM pool2 WHERE member_id='$mid'")->getRow();

        $pool3 = $this->db->query("SELECT * FROM pool3 WHERE member_id='$mid'")->getResult();
        $pool3D = $this->db->query("WITH RECURSIVE member_levels AS (
            SELECT 
                m.user_id,
                m.member_id,
                m.upline_id,
                0 AS level
            FROM (
                SELECT *
                FROM pool3
                WHERE member_id = '$mid'
                ORDER BY user_id ASC
                LIMIT 1
            ) AS m
        
            UNION ALL
        
            -- Recursive query: get children
            SELECT 
                c.user_id,
                c.member_id,
                c.upline_id,
                ml.level + 1 AS level
            FROM pool3 c
            INNER JOIN member_levels ml 
                ON c.upline_id = ml.user_id
        )
        SELECT 
            level,
            COUNT(*) AS total
        FROM member_levels WHERE level>0 AND level<=5
        GROUP BY level
        ORDER BY level;")->getResult();
        $pool3Total = $this->db->query("SELECT SUM(upgrade_balance) as upgrade_balance, SUM(income) as income FROM pool3 WHERE member_id='$mid'")->getRow();

        $pool_income = ($pool1Total->income ?? 0) + ($pool2Total->income ?? 0) + ($pool3Total->income ?? 0);
        $pool_upgrade = ($pool1Total->upgrade_balance ?? 0) + ($pool2Total->upgrade_balance ?? 0) + ($pool3Total->upgrade_balance ?? 0);
        return [
            'pool1Total' => $pool1Total,
            'pool2Total' => $pool2Total,
            'pool3Total' => $pool3Total,
            'pool1' => $pool1,
            'pool2' => $pool2,
            'pool3' => $pool3,
            'pool1D' => $pool1D,
            'pool2D' => $pool2D,
            'pool3D' => $pool3D,
            'pool_income' => $pool_income,
            'pool_upgrade' => $pool_upgrade,
        ];
    }
    public function income($mid)
    {
        $memberD = $this->db->query("SELECT wallet, user_wallet, salary_rank FROM members WHERE member_id='$mid'")->getRow();

        $all_upgrades = $this->db->query("SELECT *, date(date) as up_date FROM upgrades WHERE member_id='$mid' AND withdrawal_limit<1000")->getResult();

        $i = 1;
        foreach ($all_upgrades as $up) {
            $up_date = $up->up_date;
            $count_total = $this->db->query("SELECT count(*) as total FROM members as m
                                        INNER JOIN upgrades as u ON u.member_id=m.member_id
                                        WHERE m.sponsor_id='$mid'")->getRow()->total ?? 0;

            if ($count_total >= 2 * $i) {
                $this->db->query("update upgrades SET withdrawal_limit=1000 WHERE id=$up->id");
            } else {
                // Condition 2: every month after 2 months → +100 until 1000
                $today = date('Y-m-d');
                $months_passed = (new DateTime($up_date))->diff(new DateTime($today))->m
                    + (new DateTime($up_date))->diff(new DateTime($today))->y * 12;

                if ($months_passed > 2) { // only start after 2 months
                    $extra_months = $months_passed - 2; // months beyond 2 months
                    $increment = $extra_months * 100;

                    $new_limit = min(1000, $up->withdrawal_limit + $increment);

                    if ($new_limit > $up->withdrawal_limit) {
                        $this->db->query("UPDATE upgrades SET withdrawal_limit=$new_limit WHERE id={$up->id}");
                    }
                }
            }
            $i++;
        }

        $upgrade = $this->db->query("SELECT SUM(upgrade_amount) as total, SUM(withdrawal_limit) as total_limit FROM upgrades WHERE member_id='$mid'")->getRow();

        $roi = 0;
        $level = 0;
        $pair = 0;
        $autopool = 0;
        $pool_upgrade = 0;
        $salary = 0;
        $royality = 0;
        $total_pair = 0;
        $total_pairR = 0;
        $pool = [];
        $wallet_balance = $memberD->wallet;

        if (isset($upgrade->total) and $upgrade->total > 0) {

            $roiIncome = $this->roiIncomeData($mid);

            if (isset($roiIncome['total'])) {
                $roi = $roiIncome['total'];
            }

            $matcingB = $this->matchingIncome($mid); //today_total

            if (isset($matcingB['total'])) {
                $pair = $matcingB['total'];
            }

            $pool = $this->poolIncomeData($mid);
            $autopool = $pool['pool_income'];
            $pool_upgrade = $pool['pool_upgrade'];

            $salaryIncome = $this->salaryIncomeData($mid);
            $salary = $salaryIncome['total'];
            $total_pair = $salaryIncome['total_pair'];
            $total_pairR = $salaryIncome['total_pairR'];


            $levelIncome = $this->levelIncomeData($mid);
            $level = $levelIncome['total'];
        }

        $reinvestresult = $this->db->query("SELECT SUM(amount) as total FROM txn_details WHERE member_id='$mid' AND type='reinvestment'")->getRow();
        $reinvest = 0;
        /* if (isset($reinvestresult->total) and $reinvestresult->total > 0) {
            $reinvest = $reinvestresult->total;
        } */

        //$withdrawalsresult = $this->db->query("SELECT SUM(amount) as total FROM txn_details WHERE member_id='$mid' AND type='withdrawal' AND transfer_from IS NULL")->getRow();
        $withdrawalsresult = $this->db->query("SELECT SUM(amount) as total FROM txn_details WHERE member_id='$mid' AND type='withdrawal' AND (transfer_from is NULL or transfer_from='income')")->getRow();
        $withdrawal = 0;

        if (isset($withdrawalsresult->total) and $withdrawalsresult->total > 0) {

            $withdrawal = $withdrawalsresult->total;
        }
        $totalIncome = $roi + $level + $pair + $autopool + $salary + $royality;

        $limit = $upgrade->total_limit;
        $limit_10x = $upgrade->total * 10;
        if ($totalIncome > $limit) {
            $totalIncome = $limit;
        }
        $totalIncome = round($totalIncome, 2);

        $can_withdrawal = $totalIncome - $withdrawal;

        $limitRemaining = $limit_10x - $totalIncome;
        if ($limitRemaining <= 0) {
            $limitRemaining = 0;
        }
        $sal1 = 'Pair 25/' . $total_pair;
        $sal2 = 'Pair 50/' . $total_pair;
        $sal3 = 'Pair 100/' . $total_pair;
        $sal4 = 'Pair 500/' . $total_pairR;

        if ($total_pair >= 25) {
            $sal1 = "Achieved";
        }
        if ($total_pair >= 50) {
            $sal2 = "Achieved";
        }
        if ($total_pair >= 100) {
            $sal3 = "Achieved";
        }
        if ($total_pairR >= 500) {
            $sal3 = "Achieved";
        }

        return  [
            'limitRemaining' => $limitRemaining,
            'roiIncome' => $roi,
            'levelIncome' => $level,
            'pairIncome' => $pair,
            'autopoolIncome' => $autopool,
            'pool_upgrade' => $pool_upgrade,
            'pool' => $pool,
            'salaryIncome' => $salary,
            'royalityIncome' => $royality,
            'upgrade' => $upgrade->total,
            'totalIncome' => $totalIncome,
            'can_withdrawal' => $can_withdrawal,
            'total_withdrawal' => $withdrawal,
            'wallet_balance' => $wallet_balance,
            'limit' => $limit,
            'totalBusiness' => 0,
            'directIncome' => 0,
            'dailyIncome' => 0,
            'rewardIncome' => 0,
            'user_wallet' => $memberD->user_wallet,
            'salaryIncomeLevels' => [
                [
                    'img_src' => 'assets/img/silver-badge.png',
                    'title' => 'Silver',
                    'description' => $sal1,
                ],
                [
                    'img_src' => 'assets/img/gold.png',
                    'title' => 'Gold',
                    'description' => $sal2,
                ],
                [
                    'img_src' => 'assets/img/diamond.png',
                    'title' => 'Diamond',
                    'description' => $sal3,
                ]
            ],
            'royaltyIncomeLevels' => [
                [
                    'img_src' => 'assets/img/platinum.png',
                    'title' => 'Platinum',
                    'description' => $sal4,
                ]
            ],
        ];
    }


    public function totalBusiness($mid)
    {
        $total = 0;

        $result = $this->db->query("with recursive Team as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date, u.upgrade_amount, 1 as level from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, tl.position, uu.date as active_date, uu.upgrade_amount, level+1 as level from Team as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT sum(total) as total FROM(SELECT *, sum(upgrade_amount) as total FROM Team tt GROUP BY tt.member_id)aa;")->getRow();
        if (isset($result->total)) {
            $total = $result->total;
        }

        return $total;
    }

    public function todayBusiness($mid)
    {
        $total = 0;
        $date = date('Y-m-d');
        $result = $this->db->query("with recursive Team as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date, u.upgrade_amount, 1 as level from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, tl.position, uu.date as active_date, uu.upgrade_amount, level+1 as level from Team as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT sum(total) as total FROM(SELECT *, sum(upgrade_amount) as total FROM Team tt WHERE active_date LIKE '%$date%' GROUP BY tt.member_id)aa;")->getRow();
        if (isset($result->total)) {
            $total = $result->total;
        }

        return $total;
    }



    public function matchingIncome($mid)
    {
        $totalIncome = $this->db->query("SELECT SUM(income) as total FROM matching_income WHERE member_id='$mid'")->getRow()->total ?? 0;
        $roiData = $this->db->query("SELECT date, slot, leftM, rightM, carry_L, carry_R, pair, income, capping, after_capping as final_income FROM matching_income WHERE member_id='$mid' ORDER BY id DESC ")->getResult();

        return [
            'total' => $totalIncome,
            'data' => $roiData,
        ];
    }
    public function leftrightbusiness($mid)
    {

        $results = $this->db->query("with recursive Team as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date, u.upgrade_amount, 1 as level from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, tl.position, uu.date as active_date, uu.upgrade_amount, level+1 as level from Team as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT position,round(sum(total)) as total FROM(SELECT *, sum(upgrade_amount) as total FROM Team tt  GROUP BY tt.member_id)aa GROUP by position")->getResult();

        $leftB = 0;
        $rightB = 0;
        foreach ($results as $result) {
            $result = (object)$result;
            $total = 0;
            if ($result->total) {
                $total = $result->total;
            }
            if (isset($result->position) and $result->position == 'left') {
                $leftB = $total;
            } else {
                $rightB = $total;
            }
        }
        $matching = $leftB;
        if ($leftB > $rightB) {
            $matching = $rightB;
        }
        $income = $matching * 5 / 100;
        $data = [];
        $data[] = [
            'left_business' => $leftB,
            'right_business' => $rightB,
            'matching' => $matching,
            'income' => $income,
        ];
        return [
            'leftB' => $leftB,
            'rightB' => $rightB,
            'matching' => $matching,
            'total' => $income,
            'data' => $data
        ];
    }
    public function directIncomeData($mid)
    {

        $upgrade = $this->db->query("SELECT SUM(upgrade_amount) as total, date FROM upgrades WHERE member_id='$mid'")->getRow();
        $total = 0;
        $today_total = 0;
        $directData = [];
        if (isset($upgrade->total) and $upgrade->total > 0) {
            $date = date('Y-m-d');
            $directIncome = $this->db->query("SELECT SUM(income) as total FROM direct_income WHERE member_id='$mid' AND date>='$upgrade->date'")->getRow();
            $today_totalData = $this->db->query("SELECT SUM(income) as total FROM direct_income WHERE member_id='$mid' AND date LIKE '%$date%'")->getRow()->total;
            if (isset($today_totalData)) {
                $today_total = $today_totalData;
            }
            $directData = $this->db->query("SELECT income_from as member_id, m.name, upgrade_amount as upgrade, income, date FROM direct_income as di
                INNER JOIN members as m ON m.member_id=di.income_from
             WHERE di.member_id='$mid' AND di.date>='$upgrade->date' ORDER BY date DESC")->getResult();

            if (isset($directIncome->total)) {
                $total = $directIncome->total;
            }
        }
        return [
            'total' => $total,
            'today_total' => $today_total,
            'data' => $directData,
        ];
    }

    public function levelIncomeData($mid)
    {
        $totalIncome = $this->db->query("SELECT SUM(income) as total FROM level_income WHERE member_id='$mid'")->getRow()->total ?? 0;
        $levelData = $this->db->query("SELECT income_from, level, income, date FROM level_income WHERE member_id='$mid'")->getResult();
        return [
            'total' => $totalIncome,
            'data' => $levelData,
        ];
    }

    public function roiIncomeData($mid)
    {

        $totalIncome = $this->db->query("SELECT SUM(income) as total FROM roi WHERE member_id='$mid' AND status=1")->getRow()->total ?? 0;
        $roiData = $this->db->query("SELECT date, up_date as invest, up_amount, income FROM roi WHERE member_id='$mid' ORDER BY id DESC ")->getResult();

        return [
            'total' => $totalIncome,
            'data' => $roiData,
        ];
    }

    public function salaryIncomeDataOLD($mid)
    {
        $this->db->query("UPDATE members m
        JOIN (
            SELECT 
                mi.member_id,
                SUM(mi.pair) AS total
            FROM matching_income mi
            JOIN (
                SELECT member_id, DATE(date) AS active_date
                FROM upgrades
                WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
            ) u ON u.member_id = mi.member_id
            WHERE mi.date BETWEEN u.active_date AND DATE_ADD(u.active_date, INTERVAL 1 MONTH)
            GROUP BY mi.member_id
        ) t ON t.member_id = m.member_id 
        SET m.salary_rank = CASE
            WHEN t.total >= 100 THEN 3
            WHEN t.total >= 50  THEN 2
            WHEN t.total >= 25  THEN 1
            ELSE 0
        END;
        ");
        $date = "2025-09-22";
        $date_start = date('Y-m-01');

        $is_added = $this->db->query("SELECT COUNT(*) as total FROM salary_income WHERE date='$date'")->getRow()->total ?? 0;
        //if (date('Y-m-d') >= $date) {
        if (0) {
            if (!$is_added) {
                $total_id = $this->db->query("SELECT COUNT(*) as total FROM `upgrades` WHERE date(date) BETWEEN '$date_start' AND '$date'")->getRow()->total ?? 0;
                $total_income = $total_id * 5;

                $rank1ID = $this->db->query("SELECT COUNT(*) as total FROM members WHERE salary_rank>=1")->getRow()->total ?? 0;
                $rank2ID = $this->db->query("SELECT COUNT(*) as total FROM members WHERE salary_rank>=2")->getRow()->total ?? 0;
                $rank3ID = $this->db->query("SELECT COUNT(*) as total FROM members WHERE salary_rank=3")->getRow()->total ?? 0;

                if ($rank1ID) {
                    $user_income = $total_income / $rank1ID;
                    $results = $this->db->query("SELECT * FROM members WHERE salary_rank>=1")->getResult();
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert(
                            [
                                'member_id' => $res->member_id,
                                'date' => $date,
                                'total_achiever' => $rank1ID,
                                'total_id' => $total_id,
                                'total_amount' => $total_income,
                                'rank' => 1,
                                'income' => $user_income,
                            ]
                        );
                    }
                }
                if ($rank2ID) {
                    $user_income = $total_income / $rank2ID;
                    $results = $this->db->query("SELECT * FROM members WHERE salary_rank>=2")->getResult();
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert(
                            [
                                'member_id' => $res->member_id,
                                'date' => $date,
                                'total_achiever' => $rank2ID,
                                'total_id' => $total_id,
                                'total_amount' => $total_income,
                                'rank' => 2,
                                'income' => $user_income,
                            ]
                        );
                    }
                }
                if ($rank3ID) {
                    $user_income = $total_income / $rank3ID;
                    $results = $this->db->query("SELECT * FROM members WHERE salary_rank=3")->getResult();
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert(
                            [
                                'member_id' => $res->member_id,
                                'date' => $date,
                                'total_achiever' => $rank3ID,
                                'total_id' => $total_id,
                                'total_amount' => $total_income,
                                'rank' => 3,
                                'income' => $user_income,
                            ]
                        );
                    }
                }
            }
        }

        $totalIncome = $this->db->query("SELECT SUM(income) as total FROM salary_income WHERE member_id='$mid'")->getRow()->total ?? 0;
        $roiData = $this->db->query("SELECT 
                                        `date`, 
                                        `total_achiever`, 
                                        `total_id`, 
                                        `total_amount`, 
                                        CASE 
                                            WHEN `rank` = 1 THEN 'Silver'
                                            WHEN `rank` = 2 THEN 'Gold'
                                            WHEN `rank` = 3 THEN 'Diamond'
                                            ELSE 'Unknown'
                                        END AS `rank`, 
                                        `income` 
                                    FROM salary_income 
                                    WHERE member_id='$mid' 
                                    ORDER BY id DESC;
                                    ")->getResult();

        return [
            'total' => $totalIncome,
            'data' => $roiData,
        ];
    }

    public function salaryIncomeData($mid)
    {
        $current_date = date('Y-m-d');

        $royality_date = $this->db->query("SELECT * FROM months WHERE type='royality' ORDER BY id DESC")->getRow();

        if ($royality_date) {
            $is_added = $this->db->query("SELECT count(*) as total FROM salary_income WHERE date='$royality_date->end_on' AND type='royality'")->getRow()->total ?? 0;
            if (!$is_added) {
                if ($royality_date->end_on < $current_date) {
                    $total_id = $this->db->query("SELECT COUNT(*) as total FROM `upgrades` WHERE date(date) BETWEEN '$royality_date->start_from' AND '$royality_date->end_on'")->getRow()->total ?? 0;
                    $total_income = $total_id * 2;


                    $results  = $this->db->query("SELECT * FROM (SELECT 
                                mi.member_id,
                                SUM(mi.pair) AS total
                            FROM matching_income mi
                            JOIN (
                                SELECT member_id, DATE(date) AS active_date
                                FROM upgrades
                                WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
                            ) u ON u.member_id = mi.member_id
                            WHERE mi.date BETWEEN '$royality_date->start_from' AND '$royality_date->end_on'
                            GROUP BY mi.member_id) aa WHERE total>=500")->getResult();
                    $rank1ID = sizeof($results);
                    if ($rank1ID) {
                        $user_income = $total_income / $rank1ID;
                        foreach ($results as $res) {
                            $this->db->table('salary_income')->insert([
                                'member_id' => $res->member_id,
                                'date' => $royality_date->end_on,
                                'total_achiever' => $rank1ID,
                                'total_id' => $total_id,
                                'total_amount' => $total_income,
                                'rank' => 1,
                                'income' => $user_income,
                                'type' => 'royality',
                            ]);
                        }
                    }
                }
            }
        }


        $salary_date = $this->db->query("SELECT * FROM months WHERE type='salary' ORDER BY id DESC")->getRow();
        $is_added = $this->db->query("SELECT count(*) as total FROM salary_income WHERE date='$salary_date->end_on' AND type='salary'")->getRow()->total ?? 0;

        if (!$is_added) {
            if ($salary_date->end_on <= $current_date) {
                $total_id = $this->db->query("SELECT COUNT(*) as total FROM `upgrades` WHERE date(date) BETWEEN '$salary_date->start_from' AND '$salary_date->end_on'")->getRow()->total ?? 0;
                $total_income = $total_id * 5;

                $results  = $this->db->query("SELECT * FROM (SELECT 
                                mi.member_id,
                                SUM(mi.pair) AS total
                            FROM matching_income mi
                            JOIN (
                                SELECT member_id, DATE(date) AS active_date
                                FROM upgrades
                                WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
                            ) u ON u.member_id = mi.member_id
                            WHERE mi.date BETWEEN '$salary_date->start_from' AND '$salary_date->end_on'
                            GROUP BY mi.member_id) aa WHERE total>=25")->getResult();
                $rank1ID = sizeof($results);
                if ($rank1ID) {
                    $user_income = $total_income / $rank1ID;
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert([
                            'member_id' => $res->member_id,
                            'date' => $salary_date->end_on,
                            'total_achiever' => $rank1ID,
                            'total_id' => $total_id,
                            'total_amount' => $total_income,
                            'rank' => 1,
                            'income' => $user_income,
                            'type' => 'salary',
                        ]);
                    }
                }



                $results  = $this->db->query("SELECT * FROM (SELECT 
                                mi.member_id,
                                SUM(mi.pair) AS total
                            FROM matching_income mi
                            JOIN (
                                SELECT member_id, DATE(date) AS active_date
                                FROM upgrades
                                WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
                            ) u ON u.member_id = mi.member_id
                            WHERE mi.date BETWEEN '$salary_date->start_from' AND '$salary_date->end_on'
                            GROUP BY mi.member_id) aa WHERE total>=50")->getResult();
                $rank2ID = sizeof($results);
                if ($rank2ID) {
                    $user_income = $total_income / $rank2ID;
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert([
                            'member_id' => $res->member_id,
                            'date' => $salary_date->end_on,
                            'total_achiever' => $rank2ID,
                            'total_id' => $total_id,
                            'total_amount' => $total_income,
                            'rank' => 2,
                            'income' => $user_income,
                            'type' => 'salary',
                        ]);
                    }
                }



                $results  = $this->db->query("SELECT * FROM (SELECT 
                                mi.member_id,
                                SUM(mi.pair) AS total
                            FROM matching_income mi
                            JOIN (
                                SELECT member_id, DATE(date) AS active_date
                                FROM upgrades
                                WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
                            ) u ON u.member_id = mi.member_id
                            WHERE mi.date BETWEEN '$salary_date->start_from' AND '$salary_date->end_on'
                            GROUP BY mi.member_id) aa WHERE total>=100")->getResult();
                $rank3ID = sizeof($results);
                if ($rank3ID) {
                    $user_income = $total_income / $rank3ID;
                    foreach ($results as $res) {
                        $this->db->table('salary_income')->insert([
                            'member_id' => $res->member_id,
                            'date' => $salary_date->end_on,
                            'total_achiever' => $rank3ID,
                            'total_id' => $total_id,
                            'total_amount' => $total_income,
                            'rank' => 3,
                            'income' => $user_income,
                            'type' => 'salary',
                        ]);
                    }
                }
            }
        }

        $total_pair = $this->db->query("SELECT  SUM(mi.pair) AS total
        FROM matching_income mi
        JOIN (
            SELECT member_id, DATE(date) AS active_date
            FROM upgrades
            WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
        ) u ON u.member_id = mi.member_id
        WHERE mi.date BETWEEN '$salary_date->start_from' AND '$salary_date->end_on' AND
        mi.member_id='$mid'")->getRow()->total ?? 0;


        $total_pairR = $this->db->query("SELECT  SUM(mi.pair) AS total
        FROM matching_income mi
        JOIN (
            SELECT member_id, DATE(date) AS active_date
            FROM upgrades
            WHERE id IN (SELECT MAX(id) FROM upgrades GROUP BY member_id)
        ) u ON u.member_id = mi.member_id
        WHERE mi.date BETWEEN '$royality_date->start_from' AND '$royality_date->end_on' AND
        mi.member_id='$mid'")->getRow()->total ?? 0;

        $total = $this->db->query("SELECT SUM(income) as total FROM salary_income WHERE member_id='$mid' AND type='salary'")->getRow()->total ?? 0;
        $data = $this->db->query("SELECT 
                                        `date`, 
                                        `total_achiever`, 
                                        `total_id`, 
                                        `total_amount`, 
                                        CASE 
                                            WHEN `rank` = 1 THEN 'Silver'
                                            WHEN `rank` = 2 THEN 'Gold'
                                            WHEN `rank` = 3 THEN 'Diamond'
                                            ELSE 'Unknown'
                                        END AS `rank`, 
                                        `income` 
                                    FROM salary_income 
                                    WHERE member_id='$mid' AND type='salary'
                                    ORDER BY id DESC;
                                    ")->getResult();


        $totalR = $this->db->query("SELECT SUM(income) as total FROM salary_income WHERE member_id='$mid' AND type='royality'")->getRow()->total ?? 0;
        $dataR = $this->db->query("SELECT 
                                        `date`, 
                                        `total_achiever`, 
                                        `total_id`, 
                                        `total_amount`, 
                                        CASE 
                                            WHEN `rank` = 1 THEN 'Platiminum'
                                            ELSE 'Unknown'
                                        END AS `rank`, 
                                        `income` 
                                    FROM salary_income 
                                    WHERE member_id='$mid' AND type='royality'
                                    ORDER BY id DESC;
                                    ")->getResult();


        return [
            'total_pair' => $total_pair,
            'total_pairR' => $total_pairR,
            'total' => $total,
            'data' => $data,

            'totalR' => $totalR,
            'dataR' => $dataR,

        ];
    }

    public function binaryIncomeData($mid)
    {

        $total = 100;
        $roiBinary = [];
        return [
            'total' => $total,
            'data' => $roiBinary,
        ];
    }

    public function royalityIncomeData($mid)
    {
        $total = 0;
        $data = [];
        return [
            'total' => $total,
            'data' => $data,
        ];
    }
    public function rewardIncomeData($mid)
    {

        $upgrade = $this->db->query("SELECT SUM(upgrade_amount) as total FROM upgrades WHERE member_id='$mid'")->getRow();
        $total = 0;
        $rewardData = [];

        $totalB = $this->totalLegBusiness($mid);
        $legB = $this->legBusiness($mid);
        $dataB = [];
        if (isset($upgrade->total) and $upgrade->total > 0) {
            $rewardData = $this->db->query("SELECT business, (leg1*business/100) as strone_leg, (leg2*business/100) as strong_leg2, (leg3*business/100) as rest_leg, one_time, monthly, 'pending' as status  FROM reward")->getResult();
            $current_business = $totalB;
            $leg1 = $legB['leg1'];
            $leg2 = $legB['leg2'];
            $leg3 = $legB['leg3'];

            foreach ($rewardData as $reward) {
                if ($current_business > $reward->business) {
                    $current_business = $current_business - $reward->business;
                    $business = $reward->business;
                } else {
                    $business = $current_business;
                    $current_business = 0;
                }
                if ($leg1 > $reward->strone_leg) {
                    $leg1 = $leg1 - $reward->strone_leg;
                    $leg1B = $reward->strone_leg;
                } else {
                    $leg1B = $leg1;
                    $leg1 = 0;
                }
                if ($leg2 > $reward->strong_leg2) {
                    $leg2 = $leg2 - $reward->strong_leg2;
                    $leg2B = $reward->strone_leg2;
                } else {
                    $leg2B = $leg2;
                    $leg2 = 0;
                }
                if ($leg3 > $reward->rest_leg) {
                    $leg3 = $leg3 - $reward->rest_leg;
                    $leg3B = $reward->rest_leg;
                } else {
                    $leg3B = $leg3;
                    $leg3 = 0;
                }

                $status = 'Pending';
                if ($leg1B == $reward->strone_leg and $leg1B == $reward->strone_leg2 and $leg3B == $reward->rest_leg and $business == $reward->business) {
                    $status = 'Completed';
                    $total += $reward->one_time;
                }
                $business = round($business);
                $leg1B = round($leg1B);
                $leg2B = round($leg2B);
                $leg3B = round($leg3B);

                $strone_leg = round($reward->strone_leg);
                $strone_leg2 = round($reward->strong_leg2);
                $strone_leg3 = round($reward->rest_leg);


                $dataB[] = [
                    'business' => $business . "/" . $reward->business,
                    'strone_leg' => $leg1B . "/" . $strone_leg,
                    'strong_leg2' => $leg2B . "/" . $strone_leg2,
                    'rest_leg' => $leg3B . "/" . $strone_leg3,
                    'one_time' => $reward->one_time,
                    'monthly' => $reward->monthly,
                    'status' => $status,
                ];
            }
        }




        return [
            'totalB' => $totalB,
            'legB' => $legB,
            'total' => $total,
            'data' => $dataB,
        ];
    }

    public function totalLegBusiness($mid)
    {
        $total = 0;
        $result = $this->db->query("SELECT t.member_id, t.level, SUM(upgrade_amount) as total FROM team as t 
        INNER JOIN upgrades as u ON u.member_id=t.level_member
        WHERE  t.member_id='$mid'")->getRow();
        if (isset($result->total)) {
            $total = $result->total;
        }
        return $total;
    }
    public function legBusiness($mid)
    {
        $leg1 = 0;
        $leg2 = 0;
        $leg3 = 0;

        $legsB = $this->db->query("SELECT t.member_id, t.level, SUM(upgrade_amount) as total FROM team as t 
        INNER JOIN upgrades as u ON u.member_id=t.level_member
        WHERE  t.member_id='$mid' GROUP BY t.level ORDER BY total DESC")->getResult();

        if (isset($legsB[0]->total)) {
            $leg1 = $legsB[0]->total;
        }
        if (isset($legsB[1]->total)) {
            $leg2 = $legsB[1]->total;
        }

        if (sizeof($legsB) > 2) {
            for ($i = 2; $i < sizeof($legsB); $i++) {
                $leg3 += $legsB[$i]->total;
            }
        }

        return [
            'leg1' => $leg1,
            'leg2' => $leg2,
            'leg3' => $leg3,
        ];
    }
}
