<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rekap_laporan extends Model
{
    protected $table = 'order_product_recaps';
    protected $guarded = [];
    public $timestamps = false;
}
