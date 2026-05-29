<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('software_products', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('software_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('software_product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('license_orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_ref')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->index();
            $table->string('customer_phone')->nullable();
            $table->string('domain')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('paid');
            $table->timestamps();
        });

        Schema::create('purchase_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('license_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('software_product_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('activation_secret');
            $table->unsignedInteger('max_activations')->default(1);
            $table->unsignedInteger('activations_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('module_activations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('software_module_id')->constrained()->cascadeOnDelete();
            $table->string('machine_hash')->nullable();
            $table->timestamp('activated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_activations');
        Schema::dropIfExists('purchase_codes');
        Schema::dropIfExists('license_orders');
        Schema::dropIfExists('software_modules');
        Schema::dropIfExists('software_products');
    }
};
