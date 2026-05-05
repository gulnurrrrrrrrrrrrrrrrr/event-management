<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'city',
        'max_participants',
        'category_id',
        'image',   
        'user_id',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(EventImage::class)->where('is_cover', true);
    }


  
    public function getCoverUrlAttribute(): ?string
    {
        $cover = $this->coverImage;
        if ($cover) {
            return $cover->url;
        }
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
