<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Ejecuta la migración - crea la tabla
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();                          // ID autoincremental
            $table->string('nombre');              // Nombre del producto
            $table->text('descripcion')->nullable(); // Descripción (opcional)
            $table->decimal('precio', 8, 2);       // Precio (ej: 999999.99)
            $table->integer('stock')->default(0);   // Cantidad en inventario
            $table->string('categoria')->nullable(); // Categoría del producto
            $table->boolean('activo')->default(true); // Si está disponible
            $table->timestamps();                  // created_at y updated_at
        });
    }
 
    /**
     * Revierte la migración - elimina la tabla
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
