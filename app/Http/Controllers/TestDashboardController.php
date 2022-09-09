<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
use Session, Redirect, DateTime, DateTimeZone;

class TestDashboardController extends Controller
{
    public function norwayTest()
    {
        // $data['variableone'] = 'The value of variable one.';
        // $data['variabletwo'] = 'The value of variable two.';
        // $data['variablethree'] = 'The value of variable three.';

        return view('test_folder/demo_norway');
    }

    public function ukTest()
    {
        // $data['variableone'] = 'The value of variable one.';
        // $data['variabletwo'] = 'The value of variable two.';
        // $data['variablethree'] = 'The value of variable three.';

        return view('test_folder/demo_uk');
    }
}
