<?php

namespace App\Enums;

enum TransportationWay: string
{
    case Bike       = 'bike';
    case Motorcycle = 'motorcycle';
    case Car        = 'car';
    case Bicycle    = 'bicycle';
    case Walking    = 'walking';

    /**
     * كل القيم كـ array (مفيدة فى الـ migration والـ validation)
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * لعرض اسم مقروء بالعربى (اختيارى تستخدمه فى الـ Resource)
     */
    public function label(): string
    {
        return match ($this) {
            self::Bike       => 'دراجة نارية صغيرة',
            self::Motorcycle => 'موتوسيكل',
            self::Car        => 'سيارة',
            self::Bicycle    => 'دراجة هوائية',
            self::Walking    => 'مشي',
        };
    }
}
