<?php

namespace Tests\Unit;

use App\Enums\LicenseFormat;
use App\Services\LicenseKeyGenerator;
use PHPUnit\Framework\TestCase;

class LicenseKeyGeneratorTest extends TestCase
{
    public function test_extended_format_matches_expected_pattern(): void
    {
        $key = (new LicenseKeyGenerator())->generateExtended6x8Plus4Key();

        $this->assertMatchesRegularExpression(LicenseKeyGenerator::EXTENDED_6X8_4_REGEX, $key);
    }

    public function test_extended_format_has_52_non_hyphen_characters(): void
    {
        $key = (new LicenseKeyGenerator())->generateExtended6x8Plus4Key();

        $this->assertSame(52, strlen(str_replace('-', '', $key)));
    }

    public function test_extended_format_uses_9_groups_with_expected_lengths(): void
    {
        $key = (new LicenseKeyGenerator())->generateExtended6x8Plus4Key();
        $groups = explode('-', $key);

        $this->assertCount(9, $groups);
        $this->assertSame([6, 6, 6, 6, 6, 6, 6, 6, 4], array_map('strlen', $groups));
    }

    public function test_default_format_is_preserved(): void
    {
        $key = (new LicenseKeyGenerator())->generate(LicenseFormat::Default);

        $this->assertMatchesRegularExpression('/^TELPOS-[A-Z0-9]{6}-[A-Z0-9]{6}$/', $key);
    }
}
