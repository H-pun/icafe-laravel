<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = new User($request->all());
        try {
            $user->save();
            return ApiResponse::build(200, 'Success');
        } catch (\Exception $e) {
            //$e->getMessage()
            return ApiResponse::build(500, 'Failed', 'failed to insert to database');
        }
    }
}
