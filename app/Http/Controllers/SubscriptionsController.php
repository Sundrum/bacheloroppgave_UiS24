<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\Irrigationrun;
use App\Models\Api;
use App\Models\Activity;
use Lang;
use App\Models\Customer;
use App\Models\Treespecies;
use Auth, Session, Redirect, DateTime, DateTimeZone, DB, Log;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MapController;

class SubscriptionsController extends Controller
{
    public function subscriptions() {
        return view('pages.subscriptions');
    }
}