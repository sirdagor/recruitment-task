<?php

namespace App\Invoice\Infrastructure\Repository\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Money\Money;
use Money\Currency;

/**
 * 2 precision money type, using decimal as an SQL type, and integer as PHP type.
 */
class MoneyType extends Type
{
    public const MONEY = 'money';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        // Define the database column type to store the money value.
        // For example, you can use a string to store the amount in cents.
        return 'VARCHAR(255)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Money
    {
        // Convert the value from the database to a Money object.
        return new Money((int) $value, new Currency('USD'));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->getAmount();
    }

    public function getName(): string
    {
        return self::MONEY;
    }
}
