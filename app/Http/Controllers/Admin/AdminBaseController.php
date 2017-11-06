<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Cache;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        if (session('admin_user_id')) {
            Cache::put('admin_user_id'.session('admin_user_id'), 15);
        }
    }
}
