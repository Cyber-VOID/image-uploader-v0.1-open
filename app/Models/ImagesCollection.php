<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class ImagesCollection extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'collection_name', 'ispublic', 'token', 'path'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->attributes['token'] = self::generateToken();
        });
        static::updating(function ($model) {
            $model->attributes['token'] = self::generateToken();
        });
    }

    public function url(): MorphOne
    {
        return $this->morphOne(PublicUrls::class, 'urlable');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Images::class, 'collection_id', 'id');
    }

    public static function generateToken(): string
    {
        return Str::random(32);
    }
}
