<?php

namespace App\Invoice\Application\DTO;

use App\Invoice\Domain\Entity\Payment;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;

class BalanceView implements \JsonSerializable
{
    public function __construct(
        private readonly string $amount,
        private readonly string $currency
    ) {

    }

    public static function fromPayment(Payment $payment)
    {
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        return new self(
            $moneyFormatter->format($payment->amount()),
            $payment->currency(),
        );
    }


    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency
        ];
    }


}
