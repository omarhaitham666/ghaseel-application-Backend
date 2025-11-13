<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'image', // Keep for backward compatibility
        'is_active',
    ];

    protected $casts = [
        'name' => 'array', 
        'description' => 'array',
        'is_active' => 'boolean',
    ];

  


    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile(); // Only one image per service
    }

    /**
     * Get the carts for the service.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the order items for the service.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the image URL attribute.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('images');
        if ($media) {
            return $media->getUrl();
        }

        // Fallback to old image field
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return null;
    }

    /**
     * Append image_url to model array/json.
     *
     * @var array
     */
    protected $appends = ['image_url'];
}