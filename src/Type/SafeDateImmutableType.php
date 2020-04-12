<?php

declare(strict_types=1);

namespace DobryProgramator\DoctrineSafeTypes\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateImmutableType;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;
use Safe\Exceptions\DatetimeException;

class SafeDateImmutableType extends DateImmutableType
{
    public function getName(): string
    {
        return Types::SAFE_DATE_IMMUTABLE;
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?SafeDateTimeImmutable
    {
        if ($value === null || $value instanceof SafeDateTimeImmutable) {
            return $value;
        }

        try {
            $dateTime = SafeDateTimeImmutable::createFromFormat('!' . $platform->getDateFormatString(), $value);
        } catch (DatetimeException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString()
            );
        }

        return $dateTime;
    }
}
