<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('detections', function (Blueprint $table) {
      // Crea el índice compuesto (id_zona, fecha)
      $table->index(['id_zona', 'fecha'], 'detections_id_zona_fecha_idx');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('detections', function (Blueprint $table) {
      // Elimina el índice en caso de rollback
      $table->dropIndex('detections_id_zona_fecha_idx');
    });
  }
};
