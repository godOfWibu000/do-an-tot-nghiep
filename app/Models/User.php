<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAllUsers($tuKhoa, $sapXep){
        $list = DB::table($this->table)
        ->where('role', '=', 'USER');

        if(!empty($tuKhoa))
            $list = $list->where('name', 'LIKE', '%'.$tuKhoa.'%');
        if(!empty($sapXep))
            $list = $list->orderBy('name', $sapXep);
        else
            $list = $list->orderBy('name', 'ASC');

        $list = $list->paginate(1);

        return $list;
    }

    public function deleteUser($id){
        return DB::table($this->table)
        ->where('id', '=', $id)
        ->delete();
    }
}
