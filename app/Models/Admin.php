<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // هذا ضروري
use Illuminate\Notifications\Notifiable;
// إذا كنت تستخدم Laravel Sanctum مع الأدمن:
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable // يجب أن يمتد من Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // أضف HasApiTokens إذا كنت تستخدم Sanctum

    // تحديد الـ guard لهذا الـ Model (اختياري ولكنه مفيد)
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // إذا كان اسم جدول الأدمن ليس 'admins'
    // protected $table = 'your_custom_admin_table_name';
}