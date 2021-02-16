<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserWelcomeMail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // This funciton is called when we boot up this Model
    protected static function boot()
    {
        parent::boot();

        // Here we want to create a profile for every user that was just created the first time
        // Of the profile the only default field will be the title, the rest will be empty
        static::created(function ($user) {
            $user->profile()->create([
                'title' => $user->username,
            ]);

            Mail::to($user->email)->send(new NewUserWelcomeMail());
        });

        // Send a Welcome Email for a user that just register

    }

    // Function to relate the Profile model with the User Model
    // If we ask for a user, we can get it's profile
    // In this case we want a 1-to-1 relationship (1 user - 1 profile)
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // We named the functions with an -s because one user can have multiple posts.
    // In this case we want a 1-to-many relationship (1 user - many posts)
    public function posts()
    {
        // With the "orderBy" we are organizing the post from newer to oldest.
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }


    // This is a relationship between the USers table and the Profile table
    // With this we stablish a "many-to-many" relation so that we can have followers
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

}
