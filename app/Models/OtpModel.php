<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'otp';
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

    public function validateOTP($member_id, $otp, $type)
    {
        
        $result = $this->db->query("SELECT * FROM otp WHERE member_id='$member_id' AND otp_for='$type' AND status=0 ORDER BY id DESC")->getRow();
        $mem = $this->db->query("SELECT member_id, password, 'app.apk' as link FROM members WHERE member_id='$member_id'")->getRow();
        
        if (isset($result->id)) {
            if ($result->otp == $otp) {
                if ($type == 'Register') {
                    $this->db->query("UPDATE members SET status=1 WHERE member_id='$member_id'");
                }
                $this->db->query("UPDATE otp SET status=1 WHERE id=$result->id");
                return [
                    'status' => 'success',
                    'data'=>$mem,
                    'message' => 'OTP validated successfully...',
                ];
            }
        }
        return [
            'status' => 'fail',
            'message' => 'Invalid OTP, Please enter correct OTP',
        ];
    }
}
