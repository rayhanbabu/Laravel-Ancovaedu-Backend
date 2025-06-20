<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'date',
        'content',
        'link',
        'image',
        'page_category_id',
        'status',
        'serial',
        'name',
        'phone',
        'email',
        'designation'
    ];

    protected $casts = [
        'status' => 'boolean',
        'date' => 'date',
    ];

    public function pageCategory()
    {
        return $this->belongsTo(PageCategory::class, 'page_category_id');
    }
}
