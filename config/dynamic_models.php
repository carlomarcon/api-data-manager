<?php

use App\Models\Categoria;
use App\Models\Prodotto;

return [
  /*
    Questo array mappa il "tab"
    parametro dalla richiesta API
    alla relativa tabella
  */
  "mapping" => [
    "prodotti" => Prodotto::class,
    "categorie" => Categoria::class,
  ],
];
