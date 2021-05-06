<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','description','user_id','price','amount', 'filename', 'url', 'services_terms', 'pdf_generate', 'pdf_signed'
    ];
}
