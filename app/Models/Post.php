<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    // With this we are telling Laravel to not protect data added into a form
    protected $guarded = [];

    // Function to relate the Post model with the User Model
    // We are assigning a Post to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
