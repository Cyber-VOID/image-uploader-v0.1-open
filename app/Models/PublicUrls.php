<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicUrls extends Model
{
    protected $table = 'public_urls';

    protected $fillable = ['owner_id', 'isimage', 'ispublic', 'name', 'slug', 'token', 'short_url', 'image_id', 'collection_id'];

    use HasFactory;

    const TYPE = ['image', 'collection'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->attributes['token'] = self::generateToken();
            $model->attributes['short_url'] = self::generateShortUrl();
        });

        static::updating(function ($model) {
            $model->attributes['token'] = self::generateToken();
        });
    }

    public function urlable()
    {
        return $this->morphTo();
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static function generateToken(): string
    {
        return Str::random(32);
    }

    public static function generateShortUrl(): string
    {
        return Str::random(4);
    }
}
