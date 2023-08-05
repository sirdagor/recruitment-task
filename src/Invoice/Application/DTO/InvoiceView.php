<?php

namespace App\Invoice\Application\DTO;

use App\Invoice\Domain\Entity\Invoice;
use Ramsey\Uuid\UuidInterface;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;

class InvoiceView implements \JsonSerializable
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly string        $nett,
        private readonly string        $grossAmount,
        private readonly string        $tax,
        private readonly string        $currency
    ) {

    }

    public static function fromInvoice(Invoice $invoice): InvoiceView
    {
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return new self(
            $invoice->id(),
            $moneyFormatter->format($invoice->nettAmount()),
            $moneyFormatter->format($invoice->grossAmount()),
            $moneyFormatter->format($invoice->taxAmount()),
            $invoice->currency()->getCode()
        );
    }


    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nett' => $this->nett,
            'gross' => $this->grossAmount,
            'tax' => $this->tax,
            'currency' => $this->currency
        ];
    }


}
