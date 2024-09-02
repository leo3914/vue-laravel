<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, $filters)
    {
        // dd($filters);
        if (isset($filters['category'])) {
            // dd('query code condition');
            $query->whereHas('category', function ($catQuery)  use ($filters) {
                $catQuery->where('name', $filters['category']);
            });
        }
    }
}
