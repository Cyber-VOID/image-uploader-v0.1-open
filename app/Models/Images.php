<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikey', 'owner_id', 'slug', 'name', 'image_name', 'path', 'ispublic', 'token', 'collection_id',
    ];

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

    public function collection(): BelongsTo
    {
        return $this->belongsTo(ImagesCollection::class, 'collection_id');
    }

    public function url(): MorphMany
    {
        return $this->morphMany(PublicUrls::class, 'urlable');
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
}
