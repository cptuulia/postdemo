<?php

/**
 * Get a payment link from Mollie
 */

namespace App;

use Mollie\Api\Http\Data\Money;
use Mollie\Api\Http\Requests\CreatePaymentRequest;
use App\PaymentTokens;
use Mollie\Api\Resources\PaymentLink;

class Mollie
{
    public static function Pay(array $data)
    {

        $data = json_decode($data['form'], true);

        $token = PaymentTokens::create();
        $locale = self::getLocale($data);
        $amount = self::getAmount($data);
        $description = self::getDescription($data);

        $apiKey = getenv('MOLLIE_API_KEY');
        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($apiKey);


        $redirectUrl = getenv('MOLLIE_REDIRECT_URL') . $locale . '/?section=step4&paymenttoken=' . $token;

        $params = [
            "amount" => new Money('EUR', $amount),

            "description" =>  $description,
            "redirectUrl" =>  $redirectUrl,
            "webhookUrl" => "https://webshop.example.org/payments/webhook/",
            'method' => 'ideal'
        ];
        /** @var \Mollie\Api\Resources\Payment $payment */
        $payment = $mollie->payments->create($params);

        return $payment->getCheckoutUrl();
    }

    /**
     *  getLocale
     */
    private static function getLocale(array $data): string
    {
        return str_replace('-', '_', $data['locale']);
    }

    /**
     * getAmount
     */
    private static function getAmount(array $data): string
    {
        return  number_format((float)$data['paymentChoice'], 2, '.', '');
    }

    /**
     * getDescription
     */
    private static function getDescription(array $data): string
    {
        return   $data["senderFirstName"] . ' ' .
            $data["senderLastName"] ;
    }
}
