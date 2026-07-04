<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'user_id',
        'rating',
        'comment',
    ];

   

   
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /***
     * 
     * use Illuminate\Validation\Rule;

return [
    'meal_id' => ['required', 'exists:meals,id'],
    'rating' => ['required', 'integer', 'min:1', 'max:5'],
    'comment' => ['nullable', 'string', 'max:1000'],
];
     */
}
