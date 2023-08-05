<?php

namespace App\Invoice\Infrastructure\Repository\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Invoice\Domain\Entity\CustomerId;

/**
 * 2 precision money type, using decimal as an SQL type, and integer as PHP type.
 */
class CustomerIdType extends Type
{
    public const CUSTOMER_ID = 'customerId';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        // Define the database column type to store the money value.
        // For example, you can use a string to store the amount in cents.
        return 'VARCHAR(36)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CustomerId
    {
        if ($value instanceof CustomerId) {
            return $value;
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        try {
            $uuid = Uuid::fromString($value);
        } catch (\Throwable $e) {
            throw ConversionException::conversionFailed($value, self::CUSTOMER_ID);
        }

        return new CustomerId($uuid);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (
            $value instanceof UuidInterface
            || (
                (is_string($value)
                    || (is_object($value) && method_exists($value, '__toString')))
                && Uuid::isValid((string) $value)
            )
        ) {
            return (string) $value;
        }

        throw ConversionException::conversionFailed($value, self::CUSTOMER_ID);
    }

    public function getName(): string
    {
        return self::CUSTOMER_ID;
    }
}
