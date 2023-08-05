<?php

declare(strict_types=1);

namespace App\Invoice\Application\QueryHandler;

use App\Invoice\Application\DTO\AccountSummary;
use App\Invoice\Application\DTO\AccountSummaryView;
use App\Invoice\Application\DTO\BalanceView;
use App\Invoice\Application\DTO\InvoiceView;
use App\Invoice\Application\Query\GetAccountSummary;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Exception\ClientNotFoundException;
use App\Invoice\Domain\Exception\ValidationException;
use App\Invoice\Domain\Repository\CustomerRepository;
use App\Invoice\Domain\Repository\InvoiceRepository;
use App\Invoice\Domain\Repository\PaymentRepository;
use App\Invoice\Domain\Type;
use Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAccountSummaryHandler
{
    public function __construct(
        private readonly PaymentRepository  $paymentRepository,
        private readonly InvoiceRepository  $invoiceRepository,
        private readonly CustomerRepository $customerRepository,
    ) {

    }

    public function __invoke(GetAccountSummary $query): AccountSummaryView
    {
        $criteria = [];
        $clientId = $query->customerId();
        if (!empty($clientId)) {
            $criteria['id'] = $clientId->toString();
        }

        $customersObj = $this->customerRepository->findPaginated(
            $criteria,
            $query->limit(),
            $query->offset()
        );

        if (empty($customersObj)) {
            throw new ClientNotFoundException("Client not found $clientId");
        }

        $accountSummaryDto = [];
        foreach ($customersObj as $customer) {
            $invoices = [];
            $payments = [];
            $lastInvoiceNumber = '';
            $customerInvoicesObj = $this->invoiceRepository->getByCustomerIdAndType($customer->id(), Type::INVOICE);
            $customerPaymentsObj = $this->paymentRepository->getByCustomerId($customer->id());
            foreach ($customerInvoicesObj as $invoice) {
                try {
                    $invoice = new Invoice(
                        $invoice->id(),
                        $invoice->type(),
                        $invoice->customerId(),
                        $invoice->number(),
                        new Money($invoice->nettAmount()->getAmount(), $invoice->currency()),
                        new Money($invoice->grossAmount()->getAmount(), $invoice->currency()),
                        new Money($invoice->taxAmount()->getAmount(), $invoice->currency()),
                        $invoice->currency(),
                        $invoice->createdAt()
                    );
                } catch (ValidationException $exception) {
                }

                $invoices[] = InvoiceView::fromInvoice($invoice);
                $lastInvoiceNumber = $invoice->number();
            }

            foreach ($customerPaymentsObj as $payment) {
                $payments[] = BalanceView::fromPayment($payment);
            }

            $accountSummaryDto[] = new AccountSummary(
                $customer->id(),
                $lastInvoiceNumber,
                $payments,
                $invoices,
            );
        }

        return new AccountSummaryView($accountSummaryDto);
    }
}
