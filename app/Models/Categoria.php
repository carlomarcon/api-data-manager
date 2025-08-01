<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
  use HasFactory;

  protected $table = "categoria";

  protected $fillable = ["name", "description"];

  // Definizione della relazione con i prodotti
  public function products()
  {
    return $this->hasMany(Product::class);
  }
}
