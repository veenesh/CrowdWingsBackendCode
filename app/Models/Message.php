<?php

namespace App\Models;

use CodeIgniter\Model;

class Message extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'message';
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

    public function getMessage($id)
    {
        return $this->db->query("SELECT * FROM `message` WHERE message_from=$id OR message_to=$id")->getResult();
    }
    
    public function sendAll()
    {
        $results = $this->db->query("SELECT * FROM members")->getResult();
        
        foreach($results as $res){
            $this->insert([
            'message'=>'Dear Valued ',
            'message_from'=>0,
            'message_to'=>$res->id,
            'created_on'=>'2023-10-24 16:03:32',
        ]);
        
        $this->insert([
            'message'=>"We have important news to share regarding our ongoing system improvements. We're pleased to report that we've successfully completed ios data transfer So please Don’t Deposit or make any kind of withdrawal till further notice. We want to express our gratitude for your patience and understanding as we work diligently to ensure a smooth and reliable system for you.",
            'message_from'=>0,
            'message_to'=>$res->id,
            'created_on'=>'2023-10-24 16:03:32',
        ]);
        
        $this->insert([
            'message'=>'Best Regards, Global Trader Profit ',
            'message_from'=>0,
            'message_to'=>$res->id,
            'created_on'=>'2023-10-24 16:03:32',
        ]);
        }
    }
}
