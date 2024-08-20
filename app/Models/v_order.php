<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_order extends Model
{
    protected $table = 'order_product_view';
    protected $guarded = [];
    public $timestamps = false;
}
