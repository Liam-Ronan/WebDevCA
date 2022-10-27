<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /* Used boot function in AppServiceProvider */
    //protected $fillable = ['title', 'email', 'description', 'website', 'date_created', 'tags'];

    public function scopeFilter($query, array $filters) {
        if($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }

        if($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')

                  ->orWhere('description', 'like', '%' . request('search') . '%')
                  
                  ->orWhere('tags', 'like', '%' . request('search') . '%')
                  
                  ->orWhere('website', 'like', '%' . request('search') . '%');
        }
    }
}