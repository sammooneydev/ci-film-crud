<?php

namespace App\Models;

use CodeIgniter\Model;

class DiaryEntryModel extends Model
{
    protected $table = "diary_entry";
    protected $primaryKey = "entry_id";

    protected $allowedFields = [
        "entry_text",
        "score",
        "date_posted",
        "user_id",
        "film_id"
    ];
}