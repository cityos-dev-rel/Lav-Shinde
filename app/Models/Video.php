<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    use HasUlids;

    public $fillable = [
        'name',
        'size',
    ];

    public function getKeyName()
    {
        return 'fileid';
    }
}
