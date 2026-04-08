<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('kmj.notification.index');
    }
}
