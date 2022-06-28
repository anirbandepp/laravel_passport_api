<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $posts = Post::where('user_id', $user->id)->get();
            if (count($posts) > 0) {
                return response()->json(['status' => 'success', 'message' => 'Post Found!', 'data' => $posts]);
            }
            return response()->json(['status' => 'fail', 'message' => 'Post Not Found!']);
        }
    }

    public function store(Request $r)
    {
        $authUser = Auth::user();

        if ($authUser) {
            $validator = Validator::make($r->all(), [
                'title' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'validation_errors' => $validator->errors()]);
            }

            $data = $r->all();
            $data['user_id'] = auth()->id();

            $post = Post::create($data);

            if ($post) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Post created successfully'
                ]);
            }
            return response()->json([
                'status' => 'fails',
                'message' => 'Failed to create post'
            ]);
        } else {
            return response()->json(['status' => 'fails', 'message' => 'Un-authorized',  403]);
        }
    }
}
