<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{

    /*
        Used in admin dashboard for graph and chat view of activity feed
        Called from route: /admin/activity/daily
    */
    public function daily() {
        $response['dashboard'] = Activity::select('users.roletype_id_ref', 'activities.*')
                                ->where('page', 'dashboard')
                                ->where('users.roletype_id_ref', '<', '50')
                                ->join('users', 'activities.userId', 'users.user_id')
                                ->orderby('activities.created_at', 'desc')
                                ->get();

        $response['admin'] = Activity::select('users.roletype_id_ref', 'activities.*')
                                ->where('page', 'dashboard')
                                ->where('users.roletype_id_ref', '>', '50')
                                ->join('users', 'activities.userId', 'users.user_id')
                                ->orderby('activities.created_at', 'desc')
                                ->get();

        $response['settings'] = Activity::select('users.roletype_id_ref', 'activities.*')
                                ->where('page', 'settings')
                                ->where('users.roletype_id_ref', '>', '50')
                                ->join('users', 'activities.userId', 'users.user_id')
                                ->orderby('activities.created_at', 'desc')
                                ->get();

        $response['all'] = Activity::select('users.roletype_id_ref', 'activities.*')
                            ->join('users', 'activities.userId', 'users.user_id')
                            ->orderby('activities.created_at', 'desc')
                            ->limit(100)
                            ->get();
        return $response;
    }
}
