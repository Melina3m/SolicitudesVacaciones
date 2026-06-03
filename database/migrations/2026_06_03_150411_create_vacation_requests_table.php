<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id();
            // Relación con el empleado que solicita
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->text('reason');
            // Estados posibles de la solicitud
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada', 'cancelada'])->default('pendiente');
            // Observaciones del supervisor o admin
            $table->text('observations')->nullable(); 
            // Historial
            $table->string('action_by')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacation_requests');
    }
};