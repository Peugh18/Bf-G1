<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique();
            $table->string('phone')->nullable(); $table->string('role')->default('Vendedor');
            $table->string('password'); $table->rememberToken(); $table->timestamps();
        });
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); $table->morphs('tokenable'); $table->text('name'); $table->string('token', 64)->unique();
            $table->text('abilities')->nullable(); $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index(); $table->timestamps();
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('document_number')->unique();
            $table->string('email')->nullable(); $table->string('phone')->nullable(); $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->string('sku')->unique(); $table->string('name');
            $table->decimal('price', 12, 2); $table->unsignedInteger('stock')->default(0); $table->timestamps();
        });
    }
    public function down(): void
    {
        foreach (['products', 'customers', 'personal_access_tokens', 'users'] as $table) { Schema::dropIfExists($table); }
    }
};
