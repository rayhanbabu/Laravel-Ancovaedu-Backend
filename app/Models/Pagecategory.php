<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagecategory extends Model
{
    use HasFactory;


    public function parent()
   {
    return $this->belongsTo(Pagecategory::class, 'parent_id');
   }


    public function children()
{
    return $this->hasMany(Pagecategory::class, 'parent_id')->with('children');
}

  
}
