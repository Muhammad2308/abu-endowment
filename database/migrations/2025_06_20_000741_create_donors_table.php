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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('name');
            $table->string('other_name')->nullable();
            $table->string('reg_number')->unique();
            $table->string('lga');
            $table->string('nationality');
            $table->string('state');
            $table->text('address')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('profile_image')->nullable();
            $table->string('phone');
            $table->integer('entry_year')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('donor_type');
            $table->integer('ranking')->nullable();
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
