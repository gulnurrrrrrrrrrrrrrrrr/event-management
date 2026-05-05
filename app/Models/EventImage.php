<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventImage extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'path', 'is_cover', 'sort_order'];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (EventImage $image) {
            Storage::disk('public')->delete($image->path);
        });
    }
}
