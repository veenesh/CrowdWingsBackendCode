<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tokens';
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

    public function forexPerChange($live_rate, $token){
        $currentTimestamp = time();
        $twentyFourHoursAgo = $currentTimestamp - (24 * 60 * 60);

        $rate = $this->db->query("SELECT * FROM forex WHERE token='$token' AND timestamp>$twentyFourHoursAgo ORDER BY id DESC")->getRow();
        $token = $this->db->query("SELECT * FROM tokens WHERE name='$token'")->getRow();
        $rrate = $token->live_rate;
        if(isset($rate->rate)){
            $rrate=$rate->rate;
        }
        
        return $per = (($live_rate - $rrate) / $rrate) * 100;
    }
}
