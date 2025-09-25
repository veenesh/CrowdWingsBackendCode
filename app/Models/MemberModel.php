<?php

namespace App\Models;

use App\Controllers\Api\Auth;
use CodeIgniter\Model;
use DateInterval;
use DateTime;

class MemberModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'members';
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


    public function getData($member_id){

        return $this->db->query("SELECT m.id, m.member_id, m.country_code, m.phone,  m.user_wallet, m.wallet_address, m.wallet, m.sponsor_id,  m.name, m.image, m.email, m.status, date(m.created_at) as register_date, u.id as up_id, date(u.date) as active_date, (SELECT max(upgrade_amount) FROM upgrades WHERE member_id=m.member_id) as max_pack, round((SELECT SUM(upgrade_amount) FROM upgrades WHERE member_id=m.member_id)) as total_pack FROM members as m 
                            LEFT JOIN upgrades as u ON u.member_id=m.member_id
                            WHERE m.member_id='$member_id'GROUP BY m.member_id")->getRow();
    }

    public function updateTree($temp_under_userid, $side, $mid)
    {
        $temp_side_count = $side . 'count'; //leftcount or rightcount
        $temp_side_countM = $side . 'M'; //leftcount or rightcount
        $temp_side = $side;
        $total_count = 1;
        $dataTree = [];
        while ($total_count > 0) {
            $dataTree[] = [
                'member_id' => $temp_under_userid,
                'member_added' => $mid,
                'position' => $temp_side_countM,
                'date' => date('Y-m-d'),
            ];

            //income
            if ($temp_under_userid != "") {
                //change under_userid
                $next_under_userid = $this->getUnderId($temp_under_userid);
                $temp_side = $this->getUnderIdPlace($temp_under_userid);
                $temp_side_count = $temp_side . 'count';
                $temp_side_countM = $temp_side . 'M';
                $temp_under_userid = $next_under_userid;
            }

            //Chaeck for the last user
            if ($temp_under_userid == "") {
                $total_count = 0;
            }
        }
        $this->db->table('tree_data')->insertBatch($dataTree);
    }

    public function getUnderId($userid)
    {
        //echo "UPPP = ".$userid;
        $result = $this->db->query("select * from members where member_id='$userid'")->getRow();
        return $result->upline;
    }
    public function getUnderIdPlace($userid)
    {
        $result = $this->db->query("SELECT * from members where member_id='$userid'")->getRow();
        return $result->position;
    }

    public function updateProfile($mid, $user_wallet, $otp, $img)
    {
        $getmem = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        

        if ($otp == null) {
            $AUTH = new Auth();
            $memD = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
            $otp = $AUTH->sendOTP($memD->member_id, 'ProfileUpdate');
            $data = [
                'status' => 'success',
                'otp_sent' => 1,
                'message' => 'OTP send to your email id',
                'redirect' => 0,
            ];
            return $data;
        }
        $OTP = new OtpModel();
        $result = $OTP->validateOTP($mid, $otp, 'ProfileUpdate');
        if ($result['status'] == 'success') {
            $this->db->query("UPDATE members SET user_wallet='$user_wallet' WHERE member_id='$mid'");
            if($img){
                $this->db->query("UPDATE members SET image='$img' WHERE member_id='$mid'");
            }
            $data = [
                'status' => 'success',
                'message' => 'profile updated successfully..',
                'redirect' => 1,
            ];
            return $data;
        }
        return $result;
    }

    public function getDwnlineDetails($member_id, $position){

        return $this->db->query("SELECT m.id, m.member_id, m.country_code, m.phone,  m.wallet_address, m.wallet, m.sponsor_id,  m.name, m.image, m.email, m.status, u.id as up_id, (SELECT max(upgrade_amount) FROM upgrades WHERE member_id=m.member_id) as max_pack FROM members as m 
                            LEFT JOIN upgrades as u ON u.member_id=m.member_id
                            WHERE m.upline='$member_id' AND m.position='$position'")->getRow();
    }
    public function findNewSponsor($id, $position)
    {
        $new_sponsor = array();
        start:
        $sql = "SELECT member_id FROM members where upline = '$id' AND position='$position'";
        $result = $this->db->query($sql)->getRow();
        if (isset($result->member_id)) {
            $id = $result->member_id;
            goto start;
        }
        $new_sponsor['id'] = $id;
        $new_sponsor['position'] = $position;
        return $new_sponsor;
    }

    public function withdrawalList(){
        return $this->db->query("SELECT * FROM txn_details WHERE type='Withdrawal' AND hash!='' ORDER BY id DESC")->getResult();
    }
    public function findAllAdmin(){
        return $this->db->query("SELECT m.*, SUM(value) as usdt FROM members as m 
        LEFT JOIN add_fund_txn as a on a.to=m.wallet_address GROUP by m.member_id ORDER BY value DESC;")->getResult();
    }
    public function myTeamByLevel($member)
    {
        $level = 1;
        $mid = $member;
        $id_check = $this->db->query("SELECT * FROM members WHERE member_id='$member'")->getRow();
        startLevelAgain:
        $result = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        if ($result->sponsor_id != '') {
            $count = $this->db->query("SELECT id FROM my_team WHERE member_id='$result->sponsor_id' AND $level='$level' AND level_member='$member'")->getRow();
            if (!$count) {
                $data[] = [
                    'member_id' => $result->sponsor_id,
                    'level' => $level,
                    'level_member' => $member,
                    'id_check' => $id_check->member_id,
                ];
            }
            $mid = $result->sponsor_id;
            $level++;
            goto startLevelAgain;
        }
        $this->db->table('my_team')->insertBatch($data);
    }
    public function listWithdrawals($mid)
    {
        //return $this->db->query("SELECT amount, date_created as date FROM txn_details WHERE member_id='$mid' AND type='Withdrawal'")->getResult();
        $data = [];
        $total_capping = $this->db->query("SELECT SUM(capping) as total FROM upgrades WHERE member_id='$mid'")->getRow()->total;
        if ($total_capping > 0) {
            $capping = (object)[
                'amount' => $total_capping,
                'date' => 'Limit Leps',
            ];
            array_push($data, $capping);
        }
        $querySet = "SET @sr := 0;";
        $this->db->query($querySet);
        $with_results = $this->db->query("SELECT (@sr := @sr + 1) AS sr, amount, type, date_created as date, hash FROM txn_details WHERE member_id='$mid' AND type='Withdrawal'")->getResult();

        $querySet = "SET @sr := 0;";
        $this->db->query($querySet);
        $trans_results = $this->db->query("SELECT (@sr := @sr + 1) AS sr, m.member_id, m.name, t.amount,  t.date_created as date FROM txn_details as t
        LEFT JOIN members as m ON m.member_id=t.hash
         WHERE t.member_id='$mid' AND t.type='fund transfer'")->getResult();
        /* foreach ($results as $result) {
            array_push($data, $result);
            //$data[] = (object)$result;
        } */
        return [
            'withdrawals'=>$with_results,
            'transfers'=>$trans_results,
        ];
        print_r($data);
    }
    
    
    public function memberDeposites($mid)
    {

        
        return $results = $this->db->query("SELECT amount, member_id, type, date_created as date FROM txn_details WHERE hash='$mid' AND type='fund transfer'")->getResultArray();
        
    }
    
    
    public function listUpgrades($mid)
    {
        //return $this->db->query("SELECT amount, date_created as date FROM txn_details WHERE member_id='$mid' AND type='Withdrawal'")->getResult();
        $data = [];
       
        return $results = $this->db->query("SELECT upgrade_amount as amount, date as date FROM upgrades WHERE member_id='$mid'")->getResult();
        
    }
    
    public function listWithdrawalsOLD($mid)
    {
        return $this->db->query("SELECT amount, date_created as date FROM txn_details WHERE member_id='$mid' AND type='Withdrawal'")->getResult();
    }   
    public function matchingTeam($mid){
 
        $res = $this->db->query("SELECT SUM(pair) as total, SUM(leftM) as leftM, SUM(rightM) as rightM from matching_income WHERE member_id='$mid'")->getRow();
        $res2 = $this->db->query("SELECT * from matching_income WHERE member_id='$mid' ORDER BY id DESC")->getRow();
        $total_matching=$res->total??0;
        $left_matching=$res->leftM??0;
        $right_matching=$res->rightM??0;
        $left_carry=$res2->carry_L??0;
        $right_carry=$res2->carry_R??0;
        return [
            'total_matching'=>$total_matching,
            'left_matching'=>$left_matching,
            'right_matching'=>$right_matching,
            'left_carry'=>$left_carry,
            'right_carry'=>$right_carry,
        ];
    }
    public function team($mid){
        
        $teamDirect =  $this->db->query("select count(*) as total from members as m where m.sponsor_id='$mid'")->getRow()->total;
        $teamDirectActive =  $this->db->query("SELECT count(*) as total FROM (select m.member_id from members as m INNER JOIN upgrades as u ON u.member_id=m.member_id where m.sponsor_id='$mid' GROUP BY m.member_id)aa;")->getRow()->total;
        
        $teamLeft =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position from members as m where m.upline='$mid' and m.position='left'
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
        )
        select count(*) as total from TeamLeft;
        ")->getRow()->total;

        $teamRight =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position from members as m where m.upline='$mid' and m.position='right'
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
        )
        select count(*) as total from TeamLeft;
        ")->getRow()->total;

        $teamLeftActive =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='left'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position, date(uu.date) as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        select count(*) as total from(select * from TeamLeft where active_date is not null GROUP BY member_id)aa
        ")->getRow()->total;

        $teamRigtActive =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='right'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position, date(uu.date) as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        select count(*) as total from(select * from TeamLeft where active_date is not null GROUP BY member_id)aa;
        ")->getRow()->total;
        
        
        
        $teamLeftInActive =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='left'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position, date(uu.date) as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        select count(*) as total from(select * from TeamLeft where active_date is null GROUP BY member_id)aa
        ")->getRow()->total;

        $teamRigtInActive =  $this->db->query("
        with recursive TeamLeft as(
            select m.member_id, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='right'
    		
            UNION 
            select mm.member_id, mm.sponsor_id, mm.upline, mm.position, date(uu.date) as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        select count(*) as total from(select * from TeamLeft where active_date is null GROUP BY member_id)aa;
        ")->getRow()->total;
        
        
        $UP = new Upgrade();
        $todayBusiness = round($UP->todayBusiness($mid));
        return [
            'teamDirect'=>$teamDirect,
            'teamDirectActive'=>$teamDirectActive,
            'teamLeft'=>$teamLeft,
            'teamRight'=>$teamRight,
            'teamLeftActive'=>$teamLeftActive,
            'teamRightActive'=>$teamRigtActive,
            'todayBusiness'=>$todayBusiness,
            'teamLeftInActive'=>$teamLeftInActive,
            'teamRigtInActive'=>$teamRigtInActive
        ];
    }
    
    
    public function teamData($mid, $level){
        $querySet = "SET @sr := 0;";
        $this->db->query($querySet);

        if($level=='today business'){
            $date = date('Y-m-d');
            //$date = '2023-12-14';
            return $this->db->query("
            with recursive Team as(
                select m.member_id, m.name, m.sponsor_id, m.upline, m.position, u.date as active_date, u.upgrade_amount,  1 as level, (SELECT count(*) as total from upgrades WHERE member_id=m.member_id) as total_up from members as m 
                left join upgrades as u on u.member_id=m.member_id
                where m.upline='$mid'
                
                UNION 
                select mm.member_id,  mm.name, mm.sponsor_id, mm.upline, tl.position, uu.date as active_date, uu.upgrade_amount, level+1 as level, (SELECT count(*) as total from upgrades WHERE member_id=mm.member_id) as total_up from Team as tl
                inner join members as mm on tl.member_id=mm.upline
                left join upgrades as uu on uu.member_id=mm.member_id
            )
            SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, total, topup FROM (SELECT member_id, name, sponsor_id, position, upgrade_amount as total, if(total_up>1, 'Retopup', 'Topup') as topup FROM Team tt WHERE active_date LIKE '%$date%' ORDER BY active_date DESC) aa;")->getResult();
        }

        if($level=='direct'){
            return $this->db->query("
            select (@sr := @sr + 1) AS sr, member_id, name, m.phone, sponsor_id, m.position, date(m.created_at) as date from members as m where m.sponsor_id='$mid' ORDER BY created_at DESC")->getResult();
        }
        
        if($level=='direct active'){
            return $this->db->query("SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id , upline, active_date, upgrade FROM (select m.member_id, m.name, sponsor_id, upline, date(u.date) as active_date, (SELECT sum(upgrade_amount) FROM upgrades WHERE member_id=m.member_id) as upgrade from members as m INNER JOIN upgrades as u ON u.member_id=m.member_id where m.sponsor_id='$mid' GROUP BY m.member_id ORDER BY u.date DESC)aa;")->getResult();
        }
        
        if($level=='left active'){
           return  $this->db->query("
           with recursive TeamLeft as(
            select m.member_id,  m.name, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='left'
    		
            UNION 
            select mm.member_id,  mm.name, mm.sponsor_id, mm.upline, mm.position, uu.date as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, date(active_date) as active_date, total FROM(select *, (SELECT sum(upgrade_amount) FROM upgrades WHERE member_id=TeamLeft.member_id) as total from TeamLeft where active_date is not null GROUP BY member_id)aa
           ")->getResult();
        }
        if($level=='right active'){
            return  $this->db->query("
                with recursive TeamLeft as(
            select m.member_id,  m.name, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='right'
    		
            UNION 
            select mm.member_id,  mm.name, mm.sponsor_id, mm.upline, mm.position, uu.date as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, date(active_date) as active_date, total FROM(select *, (SELECT sum(upgrade_amount) FROM upgrades WHERE member_id=TeamLeft.member_id) as total from TeamLeft where active_date is not null GROUP BY member_id)aa
             ORDER BY active_date DESC")->getResult();
        }
        
        if($level=='left inactive'){
           return  $this->db->query("
           with recursive TeamLeft as(
            select m.member_id,  m.name, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='left'
    		
            UNION 
            select mm.member_id,  mm.name, mm.sponsor_id, mm.upline, mm.position, uu.date as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, date(active_date) as active_date, total FROM(select *, (SELECT sum(upgrade_amount) FROM upgrades WHERE member_id=TeamLeft.member_id) as total from TeamLeft where active_date is null GROUP BY member_id)aa
           ")->getResult();
        }
        if($level=='right inactive'){
            return  $this->db->query("
                with recursive TeamLeft as(
            select m.member_id,  m.name, m.sponsor_id, m.upline, m.position, u.date as active_date from members as m 
    		left join upgrades as u on u.member_id=m.member_id
    		where m.upline='$mid' and m.position='right'
    		
            UNION 
            select mm.member_id,  mm.name, mm.sponsor_id, mm.upline, mm.position, uu.date as active_date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
    		left join upgrades as uu on uu.member_id=mm.member_id
        )
        SELECT (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, date(active_date) as active_date, total FROM(select *, (SELECT sum(upgrade_amount) FROM upgrades WHERE member_id=TeamLeft.member_id) as total from TeamLeft where active_date is null GROUP BY member_id)aa
             ORDER BY active_date DESC")->getResult();
        }
        
        
        return $this->db->query("WITH RECURSIVE TeamLeft AS (
                                
                                SELECT  
                                    m.member_id, 
                                    m.name, 
                                    m.sponsor_id, 
                                    m.upline, 
                                    m.position, 
                                    m.created_at AS date,
                                    1 AS level
                                FROM members AS m 
                                WHERE m.upline = '$mid' AND m.position = '$level'
                            
                                UNION ALL
                            
                                
                                SELECT  
                                    mm.member_id, 
                                    mm.name, 
                                    mm.sponsor_id, 
                                    mm.upline, 
                                    mm.position, 
                                    mm.created_at AS date,
                                    tl.level + 1 AS level
                                FROM TeamLeft AS tl
                                INNER JOIN members AS mm ON tl.member_id = mm.upline
                            )
                            SELECT 
                                (@sr := @sr + 1) AS sr, 
                                member_id, 
                                name, 
                                sponsor_id, 
                                position, 
                                date AS joining_date,
                                level
                            FROM TeamLeft, (SELECT @sr := 0) AS init;
                            ")->getResult();
        
        
        /*return $this->db->query("with recursive TeamLeft as(
            select  m.member_id, m.name, m.sponsor_id, m.upline, m.position, m.created_at as date from members as m where m.upline='$mid' and m.position='$level'
            UNION 
            select mm.member_id, mm.name, mm.sponsor_id, mm.upline, mm.position, mm.created_at as date from TeamLeft as tl
            inner join members as mm on tl.member_id=mm.upline
        )
        select (@sr := @sr + 1) AS sr, member_id, name, sponsor_id, position, date as joining_date from TeamLeft ORDER BY date DESC;")->getResult();*/
    }

    public function changePassword($mid, $old, $new){
        $mem = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();

        if($mem->password!=$old){
            return [
                'status'=>'error',
                'message'=>'Please enter correct old password',
            ];
        }

        $this->db->query("UPDATE members SET password='$new' WHERE member_id='$mid'");
        return [
            'status'=>'success',
            'message'=>'Password updated successfully',
        ];
    }
}
