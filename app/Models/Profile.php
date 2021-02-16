<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    
    public function profileImage()
    {
        $imagePath = ($this->image) ? $this->image : 'profile/EufdF2HTXYui6BAFLGouIEzJITVxK3eSyC31LZQ4.png';
        return '/storage/' . $imagePath;
    }
    
    // Function to relate the Profile model with the User Model
    // We are assigning one profile to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class);
    }
}
