<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Controllers\MessagesController;
use Session;

class MessagesController extends Controller
{
    public static function getMessages() 
    {
        $data = Message::getMessages();

        $count_data = count($data);
        $sorted = array();

        for ($i = 0; $i < $count_data; $i++) {
            if (isset($data[$i]['timestamp'])) {
                $timestamp = Controller::convertTimestampToUserTimezone(trim($data[$i]['timestamp']));
                $time = mb_substr($timestamp, 0, 16);
                $sorted[$i][0] = $time;
            } else {
                $sorted[$i][0] = 'Undefined';
            }

            if (isset($data[$i]['serialnumber'])) {
                $sorted[$i][1] = trim($data[$i]['serialnumber']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['message'])) {
                $sorted[$i][2] = trim($data[$i]['message']);
            } else {
                $sorted[$i][2] = '-';
            }
            
            $sorted[$i][3] = '<button class="btn btn-danger" onclick="deleteMessage('.trim($data[$i]['message_id']).')">Delete</button>';
        }
        $data = MessagesController::flattenTable($sorted);
        return $data;
    }

    public static function flattenTable($data) {
        $rows = array();
        foreach ($data as $row) {
            $rows[] = array_values((array)$row);
        }
        $rows = json_encode($rows);

        return $rows;
    }

    public static function deleteMessage(Request $request) {
        $message_id = $request->id;
        $data = Message::deleteMessage($message_id);

        return $data;
    }

    public static function checkedUser() {
        $data = Message::getMessages();
        foreach($data as $message) {
            if (!$message['checkedbyuser']) {
                $message_id = $message['message_id'];
                $data = Message::checkedUser($message_id);
            }
        }
        Session::put('newMessages', 0);
        
        return $data;
    }

    public static function getNumberOfNewMessages() {
        $data = Message::getMessages();
        $counter = 0;
        foreach($data as $message) {
            if (!$message['checkedbyuser']) {
                $counter++;
            }
        }
        Session::put('newMessages', $counter);
    }
} 
