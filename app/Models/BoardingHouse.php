<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoardingHouse extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::deleting(function ($boardingHouse) {
            $image = $boardingHouse->thumbnail;

            if ($image && Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
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
        return Money::IDR($this->price)->format(); // Example: Rp12,345.00
    }

    // Mutator to save the price in cents (as an integer)
    public function setPriceAttribute($value): void
    {
        // Store price in cents
        $this->attributes['price'] = intval(floatval($value) * 100); // Convert to cents
    }
    // END: Methods
}
