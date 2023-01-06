<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Code extends Model
{
    use HasFactory;

    public $table = 'codes';

    public function __construct()
    {
        $this->uuid = Str::uuid();
        $this->fg_color = "#000000";
        $this->bg_color = "#FFFFFF";
        $this->description = "";
    }

    protected $fillable = [
        'name',
        'description',
        'tags',
        'link',
        'user_id',
        'fg_color',
        'bg_color',
        'scans',
    ];
}
