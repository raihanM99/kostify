<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::deleting(function ($city) {
            if ($city->forceDeleting) {
                $image = $city->image;
                $publicStorage = Storage::disk('public');

                if ($image && $publicStorage->exists($image)) {
                    $publicStorage->delete($image);
                }
            }
        });
    }

    public function BoardingHouses()
    {
        return $this->hasMany(BoardingHouse::class);
    }
}
