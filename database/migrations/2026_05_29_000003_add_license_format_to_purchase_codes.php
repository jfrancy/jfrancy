<?php

use App\Enums\LicenseFormat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table): void {
            $table->string('license_format')->default(LicenseFormat::Default->value)->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_codes', function (Blueprint $table): void {
            $table->dropColumn('license_format');
        });
    }
};
