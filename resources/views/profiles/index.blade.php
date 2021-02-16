@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img class="rounded-circle w-100" src="{{ $user->profile->profileImage() }}">
        </div>
        <div class="col-9 pt-5">
            <div class="d-flex justify-content-between align-items-baseline">
                <div class="d-flex align-items-center pb-4">
                    <div class="h3">{{ $user->username }}</div>
                    <!-- Button created on Vue. resources\js\components\ -->
                    <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                </div>
                
                @can('update', $user->profile)
                    <a href="/p/create">Add New Post</a>
                @endcan
                         
            </div>
                <!-- This can is to valide that if you are not logged in, you cannot see this -->
                @can('update', $user->profile)
                    <a href="/profile/{{ $user->id }}/edit">Edit Profile</a>
                @endcan

            <div class="d-flex">
                <div class="pr-5"><strong>{{ $postCount }}</strong>post</div>
                <div class="pr-5"><strong>{{ $followersCount }}</strong>followers</div>
                <div class="pr-5"><strong>{{ $followingCount }}</strong>following</div>
            </div>
            <div class="pt-4 font-weighr-bold">{{ $user->profile->title }}</div>
            <div>{{ $user->profile->description }}</div>
            <div><a href="">{{ $user->profile->url }}</a></div> 
        </div>    
    </div>

    <div class="row pt-5">
        @foreach($user->posts as $post)
        <div class="col-4 pb-4">
            <a href="/p/{{ $post->id }}">
                <img class="w-100" src="/storage/{{ $post->image }}" alt="">
            </a>       
        </div>
        @endforeach   
    </div>
</div>
@endsection
