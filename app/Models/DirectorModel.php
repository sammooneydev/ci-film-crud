<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorModel extends Model
{
    protected $table = "director";

    protected $primaryKey = "director_id";

    protected $allowedFields = [
        "director_name"
    ];
}