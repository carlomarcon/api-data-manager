<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create("prodotti", function (Blueprint $table) {
      $table->id();
      $table->string("codice")->unique(); // Chiave di business univoca
      $table->string("nome");
      $table->text("descrizione")->nullable();

      $table->decimal("prezzo", 8, 2)->default(0);

      // Relazione: un prodotto appartiene a una categoria, relazione uno a molti con categoria
      $table->foreignId("categoria_id")->constrained("categoria");

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("prodotti");
  }
};
