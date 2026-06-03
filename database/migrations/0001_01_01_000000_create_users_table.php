<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('empleado'); // admin, supervisor, empleado
            $table->string('position')->nullable(); // Cargo del empleado
            $table->date('entry_date')->nullable(); // Fecha de ingreso
            
      
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};