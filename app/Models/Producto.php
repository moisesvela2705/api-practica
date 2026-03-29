<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'productos';

    /**
     * Campos que se pueden llenar masivamente (mass assignment)
     * IMPORTANTE: Siempre define $fillable por seguridad
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'activo',
    ];

    /**
     * Tipos de datos para cada campo (casting automático)
     */
    protected $casts = [
        'precio'  => 'decimal:2',
        'stock'   => 'integer',
        'activo'  => 'boolean',
    ];
}