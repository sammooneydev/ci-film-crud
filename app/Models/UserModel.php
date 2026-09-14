<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = "user";

    protected $primaryKey = "user_id";

    protected $useAutoIncrement = true; //i believe the default value of this is true, but just declaring here for cohesion

    protected $allowedFields = [
        'username',
        'password_hash',
        'is_admin'        
    ];
}