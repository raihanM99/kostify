<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bonus extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::deleting(function ($bonus) {
            if ($bonus->forceDeleting) {
                $image = $bonus->image;
                $publicStorage = Storage::disk('public');

                if ($image && $publicStorage->exists($image)) {
                    $publicStorage->delete($image);
                }
            }
        });
    }

    public function boardingHouse()
    {
        return $this->belongsTo(BoardingHouse::class);
    }
}
