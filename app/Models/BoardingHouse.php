<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoardingHouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::deleting(function ($boardingHouse) {
            if ($boardingHouse->forceDeleting) {
                $image = $boardingHouse->thumbnail;
                $publicStorage = Storage::disk('public');

                if ($image && $publicStorage->exists($image)) {
                    $publicStorage->delete($image);
                }
            }
        });
    }

    // START: Associations
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function roomImages()
    {
        return $this->hasManyThrough(RoomImage::class, Room::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    // END: Associations

    // START: Methods
    // Accessor to format the price
    public function getFormattedPriceAttribute(): string
    {
        // Format the price in IDR (Rp)
        return Money::IDR($this->price, true)->format();
    }

    public function getImageAttribute($value)
    {
        return $this->thumbnail;
    }

    public function getPriceAttribute($value): string
    {
        return $value / 100;
    }

    // Mutator to save the price in cents (as an integer)
    public function setPriceAttribute($value): void
    {
        // Store price in cents
        $this->attributes['price'] = intval(floatval($value) * 100);
    }
    // END: Methods
}
