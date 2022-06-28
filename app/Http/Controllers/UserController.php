<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'validation_errors' => $validator->errors()
            ]);
        }

        $data = $r->all();
        $data['password'] = Hash::make($r->password);

        $user = User::create($data);

        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User registration successfully completed'
            ]);
        }
        return response()->json([
            'status' => 'fails',
            'message' => 'User registration fail!!!'
        ]);
    }

    public function login(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'validation_errors' => $validator->errors()
            ]);
        }

        //login
        if (Auth::attempt(['email' => $r->email, 'password' => $r->password])) {
            $user = Auth::user();
            $token = $user->createToken('usertoken')->accessToken;

            return response()->json(['status' => 'success', 'login' => true, 'token' => $token, 'data' => $user]);
        } else {
            return response()->json([
                'status' => 'fail',  'message' => 'Whoops! Something went wrong'
            ]);
        }
    }

    public function userDetails()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json(['status' => 'success', 'user' => $user]);
        }
        return response()->json(['status' => 'fail', 'message' => 'user not found']);
    }
}
