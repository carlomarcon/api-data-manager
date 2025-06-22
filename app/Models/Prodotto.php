<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
  use HasFactory;

  protected $table = "prodotti";

  protected $fillable = [
    "codice",
    "nome",
    "descrizione",
    "prezzo",
    "categoria_id",
  ];

  public function categoria()
  {
    return $this->belongsTo(Categoria::class);
  }
}
