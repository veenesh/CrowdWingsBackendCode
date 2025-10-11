<?php

namespace App\Models;

use CodeIgniter\Model;

class SliderModel extends Model
{
    protected $table = 'sliders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['image'];
}
