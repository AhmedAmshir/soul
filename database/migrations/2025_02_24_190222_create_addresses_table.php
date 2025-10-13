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
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('governorate');
            $table->string('apartment_suite')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country');
            $table->enum('address_type', ['shipping', 'billing'])->default('shipping');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
