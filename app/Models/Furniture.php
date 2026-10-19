<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    protected $table = 'furnitures';
    protected $fillable = ['name','category','material','price','dimensions','image'];

    public function getRupiahAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }
}