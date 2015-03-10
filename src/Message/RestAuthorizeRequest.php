<?php
/**
 * PayPal REST Authorize Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Authorize Request
 *
 * To collect payment at a later time, first authorize a payment using the /payment resource.
 * You can then capture the payment to complete the sale and collect payment.
 *
 * This looks exactly like a RestPurchaseRequest object except that the intent is
 * set to "authorize" (to authorize a payment to be captured later) rather than
 * "sale" (which is used to capture a payment immediately).
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal RestGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('RestGateway');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'clientId' => 'MyPayPalClientId',
 *       'secret'   => 'MyPayPalSecret',
 *       'testMode' => true, // Or false when you are ready for live transactions
 *   ));
 *
 *   // Create a credit card object
 *   // DO NOT USE THESE CARD VALUES -- substitute your own
 *   // see the documentation in the class header.
 *   $card = new CreditCard(array(
 *               'firstName' => 'Example',
 *               'lastName' => 'User',
 *               'number' => '4111111111111111',
 *               'expiryMonth'           => '01',
 *               'expiryYear'            => '2020',
 *               'cvv'                   => '123',
 *               'billingAddress1'       => '1 Scrubby Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Scrubby Creek',
 *               'billingPostcode'       => '4999',
 *               'billingState'          => 'QLD',
 *   ));
 *
 *   // Do an authorisation transaction on the gateway
 *   $transaction = $gateway->authorize(array(
 *       'amount'        => '10.00',
 *       'currency'      => 'AUD',
 *       'description'   => 'This is a test authorize transaction.',
 *       'card'          => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Authorize transaction was successful!\n";
 *       // Find the authorization ID
 *       $auth_id = $response->getTransactionReference();
 *   }
 * </code>
 *
 * Direct credit card payment and related features are restricted in
 * some countries.
 * As of January 2015 these transactions are only supported in the UK
 * and in the USA.
 *
 * @link https://developer.paypal.com/docs/integration/direct/capture-payment/#authorize-the-payment
 * @link https://developer.paypal.com/docs/api/#authorizations
 * @link http://bit.ly/1wUQ33R
 * @see RestCaptureRequest
 * @see RestPurchaseRequest
 */
class RestAuthorizeRequest extends AbstractRestRequest
{
    public function getData()
    {
        $data = array(
            'intent' => 'authorize',
            'payer' => array(
                'payment_method' => 'credit_card',
                'funding_instruments' => array()
            ),
            'transactions' => array(
                array(
                    'description' => $this->getDescription(),
                    'amount' => array(
                        'total' => $this->getAmount(),
                        'currency' => $this->getCurrency(),
                    ),
                )
            )
        );

        $items = $this->getItems();
        if ($items) {
            $itemList = array();
            foreach ($items as $n => $item) {
                $itemList[] = array(
                    'name' => $item->getName(),
                    'description' => $item->getDescription(),
                    'quantity' => $item->getQuantity(),
                    'price' => $this->formatCurrency($item->getPrice()),
                    'currency' => $this->getCurrency()
                );
            }
            $data['transactions'][0]['item_list']["items"] = $itemList;
        }

        if ($this->getCardReference()) {
            $this->validate('amount');

            $data['payer']['funding_instruments'][] = array(
                'credit_card_token' => array(
                    'credit_card_id' => $this->getCardReference(),
                ),
            );
        } elseif ($this->getCard()) {
            $this->validate('amount', 'card');
            $this->getCard()->validate();

            $data['payer']['funding_instruments'][] = array(
                'credit_card' => array(
                    'number' => $this->getCard()->getNumber(),
                    'type' => $this->getCard()->getBrand(),
                    'expire_month' => $this->getCard()->getExpiryMonth(),
                    'expire_year' => $this->getCard()->getExpiryYear(),
                    'cvv2' => $this->getCard()->getCvv(),
                    'first_name' => $this->getCard()->getFirstName(),
                    'last_name' => $this->getCard()->getLastName(),
                    'billing_address' => array(
                        'line1' => $this->getCard()->getAddress1(),
                        //'line2' => $this->getCard()->getAddress2(),
                        'city' => $this->getCard()->getCity(),
                        'state' => $this->getCard()->getState(),
                        'postal_code' => $this->getCard()->getPostcode(),
                        'country_code' => strtoupper($this->getCard()->getCountry()),
                    )
                )
            );

            // There's currently a quirk with the REST API that requires line2 to be
            // non-empty if it's present. Jul 14, 2014
            $line2 = $this->getCard()->getAddress2();
            if (!empty($line2)) {
                $data['payer']['funding_instruments'][0]['credit_card']['billing_address']['line2'] = $line2;
            }
        } else {
            $this->validate('amount', 'returnUrl', 'cancelUrl');

            unset($data['payer']['funding_instruments']);

            $data['payer']['payment_method'] = 'paypal';
            $data['redirect_urls'] = array(
                'return_url' => $this->getReturnUrl(),
                'cancel_url' => $this->getCancelUrl(),
            );
        }

        return $data;
    }

    /**
     * Get transaction description.
     *
     * The REST API does not currently have support for passing an invoice number
     * or transaction ID.
     *
     * @return string
     */
    public function getDescription()
    {
        $id = $this->getTransactionId();
        $desc = parent::getDescription();

        if (empty($id)) {
            return $desc;
        } elseif (empty($desc)) {
            return $id;
        } else {
            return "$id : $desc";
        }
    }

    /**
     * Get transaction endpoint.
     *
     * Authorization of payments is done using the /payment resource.
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment';
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new RestAuthorizeResponse($this, $data, $statusCode);
    }
}
