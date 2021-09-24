<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @param  Builder  $query
     * @param  string  $name
     * @return mixed
     */
    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }
}
