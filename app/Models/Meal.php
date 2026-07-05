<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'is_available',
        'ingredients',
    ];

    protected function casts(): array
    {
        return [
            'price'        => 'decimal:2',
            'protein'      => 'decimal:2',
            'carbs'        => 'decimal:2',
            'fat'          => 'decimal:2',
            'fiber'        => 'decimal:2',
            'is_available' => 'boolean',
            'ingredients'  => 'array',
        ];
    }

    // ── Relationships ─────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function inCartsOf(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cart_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // ── Scopes ────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function reviews()
    {
        return $this->hasMany(MealReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
