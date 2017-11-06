<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class AuthsController extends BaseController
{

    public static function checkRule($rule_id)
    {
        $rule = explode(',', $rule_id);
        if (in_array('2', $rule)) return 1;// 超级管理员权限
        return false;
    }
}
