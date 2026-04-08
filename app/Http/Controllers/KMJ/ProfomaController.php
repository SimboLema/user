<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfomaController extends Controller
{
    public function index()
    {
        return view('kmj.profoma.index');
    }
}
