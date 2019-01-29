<?php

namespace App\Http\Controllers\Manage;

use Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Route;
use App\Models\File;

class DashboardController extends Controller
{


    public function index()
    {
        return view('manage.dashboard.index');
    }
}
