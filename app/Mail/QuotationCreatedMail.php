<?php

namespace App\Mail;

use App\Models\Models\KMJ\Quotation;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class QuotationCreatedMail
{
    protected Quotation $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function send(string $toEmail, string $toName = ''): array
    {
        $quotation = $this->quotation;
        $customer  = $quotation->customer;
        $coverage  = $quotation->coverage;

        $bodyText = "
            Dear {$toName},

            A new quotation has been created. Here are the details:

            - Quotation ID   : {$quotation->id}
            - Customer       : {$customer->name}
            - Coverage       : {$coverage->name}
            - Sum Insured    : {$quotation->sum_insured}
            - Premium (incl. tax): {$quotation->total_premium_including_tax}
            - Start Date     : {$quotation->cover_note_start_date}
            - End Date       : {$quotation->cover_note_end_date}

            Please log in to the system to review and process this quotation.

            Regards,
            The Insurance Team
                    ";

        $email = (new MailtrapEmail())
            ->from(new Address('no-reply@suretech.co.tz', 'Insurance System'))
            ->to(new Address($toEmail, $toName))
            ->subject('New Quotation Created - #' . $quotation->id)
            ->category('Quotation')
            ->text($bodyText);

        $response = MailtrapClient::initSendingEmails(
            apiKey: config('services.mailtrap.api_key')
        )->send($email);

        return \Mailtrap\Helper\ResponseHelper::toArray($response);
    }
}
