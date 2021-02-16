<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        // with "findOrFail" instead of crashing, will show an error
        // With this line we can get the user and store it.
        // $user = User::findOrFail($user); --> Finds a user on the DB
        // \App\Models\User $user --> is doing the same as the previous line

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        // If you make a request within 30 seconds of the last one
        // we are going to provide the cache for "count Posts"
        // If there is none, then we return the count. 
        $postCount = Cache::remember(
            'count.posts' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->posts->count();
            }
        );

        $followersCount = Cache::remember(
            'count.followers' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->profile->followers->count();
            }
        );

        $followingCount = Cache::remember(
            'count.following' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following->count();
            }
        );

        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        //With this only the Authorized user can access this section
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            // If there is an image on the Edit request, we are saving it on a variable
            $imageArray = ['image' => $imagePath];
        }
        // With the array_merge we can have two arrays and put them together.
        // In this case, data already has an image, but with the second array we can overide that
        auth()->user()->profile->update(array_merge(
            $data,

            // If the user didn't upload an image while Editing the profile, nothing changes
            $imageArray ?? []
        ));


        return redirect("/profile/{$user->id}");
    }
}
