<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'activated_at', 'last_fetched_at'];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_fetched_at' => 'datetime',
    ];

    protected $appends = [
        'is_active',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getIsActiveAttribute()
    {
        return $this->activated_at !== null;
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('activated_at');
    }

}
