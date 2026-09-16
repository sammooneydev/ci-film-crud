<?php

namespace App\Models;

use CodeIgniter\Model;

class FilmModel extends Model
{
    protected $table = "film";

    protected $primaryKey = "film_id";

    protected $allowedFields = [
        "film_name",
        "film_desc"
    ];
}