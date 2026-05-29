<?php

namespace App\Services;

use App\Enums\LicenseFormat;
use InvalidArgumentException;

class LicenseKeyGenerator
{
    public const EXTENDED_6X8_4_REGEX = '/^[A-Z0-9]{6}(-[A-Z0-9]{6}){7}-[A-Z0-9]{4}$/';

    private const CHARSET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function generate(LicenseFormat|string|null $format = null): string
    {
        $licenseFormat = $format instanceof LicenseFormat
            ? $format
            : LicenseFormat::tryFrom($format ?: LicenseFormat::Default->value);

        if (! $licenseFormat) {
            throw new InvalidArgumentException("Unsupported license format: {$format}");
        }

        return match ($licenseFormat) {
            LicenseFormat::Default => $this->generateDefaultKey(),
            LicenseFormat::Extended6x8Plus4 => $this->generateExtended6x8Plus4Key(),
        };
    }

    public function generateDefaultKey(): string
    {
        return 'TELPOS-'.$this->randomChars(6).'-'.$this->randomChars(6);
    }

    public function generateExtended6x8Plus4Key(): string
    {
        $groups = [];

        foreach ([6, 6, 6, 6, 6, 6, 6, 6, 4] as $length) {
            $groups[] = $this->randomChars($length);
        }

        return implode('-', $groups);
    }

    private function randomChars(int $length): string
    {
        $characters = '';
        $maxIndex = strlen(self::CHARSET) - 1;

        for ($i = 0; $i < $length; $i++) {
            $characters .= self::CHARSET[random_int(0, $maxIndex)];
        }

        return $characters;
    }
}
