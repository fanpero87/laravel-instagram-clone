<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Post;

class PostsController extends Controller
{
    // With this function we make sure that you have to be authenticated before doing anything here
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');

        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);
        
        return view('posts.index', compact('posts'));
    
    }

    public function create()
    {
        return view ('posts.create');
    }

    public function store()
    {
        $data = request()->validate([
            'caption' => 'required',
            // With this we validate that you can only upload an image file
            'image' => ['required', 'image'],
        ]);
        
        // With this, we are saving the image on the "uploads" folder inside Public\Storage
        $imagePath = request('image')->store('uploads', 'public');
        
        //With this we are taking an image and "fit it" onto a square of 1200x1200
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        $image->save();

        // This will find the signed user and add the image to the it's posts
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);
    
        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Models\Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
