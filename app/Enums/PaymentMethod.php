<?php // PaymentMethod.php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';
    case MobileWallet = 'mobile_wallet';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'نقدي',
            self::BankTransfer => 'تحويل بنكي',
            self::MobileWallet => 'محفظة إلكترونية',
        };
    }
}