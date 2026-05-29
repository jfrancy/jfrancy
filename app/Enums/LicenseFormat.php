<?php

namespace App\Enums;

enum LicenseFormat: string
{
    case Default = 'default';
    case Extended6x8Plus4 = 'extended_6x8_4';

    public function label(): string
    {
        return match ($this) {
            self::Default => 'Default',
            self::Extended6x8Plus4 => 'Extended 6x8 + 4',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Default => 'Generates keys in the current TELPOS-XXXXXX-XXXXXX format.',
            self::Extended6x8Plus4 => 'Generates keys in the format XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXX.',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(
            fn (self $format): array => [
                'value' => $format->value,
                'label' => $format->label(),
                'description' => $format->description(),
            ],
            self::cases()
        );
    }
}
