<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;


class Event extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}