<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'sent',
        'sender_name',
        'is_Admin',
        'company_name',
        'is_profile_confirmed',
        'receiver',
        'validated',
    ];
}
