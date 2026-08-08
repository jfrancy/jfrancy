<?php

namespace Tests\Feature;

use App\Enums\LicenseFormat;
use App\Models\SoftwareProduct;
use App\Services\LicenseKeyGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevPortalLicenseCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_creates_license_with_extended_format(): void
    {
        $product = SoftwareProduct::query()->create([
            'name' => 'Vfd-POS Core License',
            'slug' => 'vfd-pos-core-license',
            'sku' => 'VFDPOS-CORE',
            'description' => 'Main Vfd-POS license.',
            'price' => 199.00,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/licenses', [
            'software_product_id' => $product->id,
            'customer_name' => 'Test Buyer',
            'customer_email' => 'buyer@example.com',
            'licenseFormat' => LicenseFormat::Extended6x8Plus4->value,
        ]);

        $response->assertCreated()
            ->assertJsonPath('license_format', LicenseFormat::Extended6x8Plus4->value);

        $this->assertMatchesRegularExpression(
            LicenseKeyGenerator::EXTENDED_6X8_4_REGEX,
            $response->json('purchase_code')
        );
    }

    public function test_invalid_license_format_is_rejected(): void
    {
        $product = SoftwareProduct::query()->create([
            'name' => 'Vfd-POS Core License',
            'slug' => 'vfd-pos-core-license',
            'sku' => 'VFDPOS-CORE',
            'description' => 'Main Vfd-POS license.',
            'price' => 199.00,
            'is_active' => true,
        ]);

        $this->postJson('/api/licenses', [
            'software_product_id' => $product->id,
            'customer_name' => 'Test Buyer',
            'customer_email' => 'buyer@example.com',
            'licenseFormat' => 'unknown_format',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('license_format');
    }
}
