<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\Api;


class User extends Authenticatable {
    
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'user_email', 'user_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function create($name, $email, $password) {
        $url = 'user_name='.$name.'&user_email='.$email.'&user_password='.$password;
        $data = Api::postApi('user/add?'.$url);

        return $data;
    }

    public static function updatePassword($email, $password) {
        $url = 'user_email='.$email.'&user_password='.$password;
        $data = Api::patchApi('user/update?'.$url);

        return $data;
    }

    public static function changeAccountSettings ($name, $email, $phone_work, $user_alternative_email, $user_language) {
        $user_id = Auth::user()->user_id;
        $url = 'user_id='.$user_id.'&user_name='.$name.'&user_email='.$email.'&user_phone_work='.$phone_work.'&user_alternative_email='.$user_alternative_email.'&user_language='.$user_language;
        
        $data = Api::patchApi('user/update?'.$url);
        return $data;
    }
    
    public static function getUsers() {
        $data = Api::getApi('user/list');

        return $data['result'];
    }

    public static function getUser($id) {
        $data = Api::getApi('user/list?user_id='.$id);
        return $data['result'];
    }
}
