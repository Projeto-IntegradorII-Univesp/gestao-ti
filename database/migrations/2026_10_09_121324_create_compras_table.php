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
		Schema::create('compras', function (Blueprint $table) {
			$table->id();
			$table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
			$table->integer('quantidade');
			$table->decimal('valor_unitario', 10, 2)->default(0.00);
			$table->string('fornecedor')->nullable();
			$table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
