<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   use HasFactory; 
	protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'short_description',
        'description',
        'image',
        'images',
        'regular_price',
        'sale_price',
        'SKU',
        'quantity',
        'stock_status',
        'featured',
        'width',
        'height',
        'thickness',
        
    ];
	 
	public function category()
    {
        return $this->belongsTo(Category::class); //
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class); //
    }
}