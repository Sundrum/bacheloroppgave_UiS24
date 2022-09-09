<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Unit;
use App\Models\Api;
use Session;

class Message extends Model
{
    public static function getMessages()
    {
        if (Session::get('customernumber')) {
            $customernumber = trim(Session::get('customernumber'));
        } else {
            $customernumber = trim(Auth::user()->customernumber);
        }

        $data = Api::getApi('messages/list?customernumber='.$customernumber);
        return $data['result'];
    }

    public static function deleteMessage($message_id)
    {
        $data = Api::deleteApi('messages/delete?message_id='.$message_id);

        return $data;
    }

    public static function checkedUser($message_id)
    {
        $user_id = Auth::user()->user_id;
        $data = Api::patchApi('messages/update?message_id='.$message_id.'&checkedbyuser='.$user_id.'&archived=false');
        
        return $data;
    }
}
