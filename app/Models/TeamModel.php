<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'team';
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


    public function myTeam($member)
    {
        $level = 1;
        $mid = $member;
        $id_check = $this->db->query("SELECT * FROM members WHERE member_id='$member'")->getRow();
        startLevelAgain:
        $result = $this->db->query("SELECT * FROM members WHERE member_id='$mid'")->getRow();
        if ($result->sponsor_id != '') {
            $count = $this->db->query("SELECT id FROM team WHERE member_id='$result->sponsor_id' AND $level='$level' AND level_member='$member'")->getRow();
            if (!$count) {
                $data[] = [
                    'member_id' => $result->sponsor_id,
                    'level' => $level,
                    'level_member' => $member,
                    'id_check' => $id_check->id,
                ];
            }
            $mid = $result->sponsor_id;
            $level++;
            goto startLevelAgain;
        }
        $this->db->table('team')->insertBatch($data);
    }
}
