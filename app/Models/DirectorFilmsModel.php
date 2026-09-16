<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorFilmsModel extends Model
{
    protected $table = "director_films";

    //nothing special is needed for the composite key since both ids are simply provided during operations
    protected $primaryKey = "film_id";

    protected $allowedFields = [
        "film_id",
        "director_id",
    ];
}