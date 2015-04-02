<?php
/**
 * PayPal REST Purchase Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Purchase Request
 *
 * PayPal provides various payment related operations using
 * the /payment resource and related sub-resources. Use payment
 * for direct credit card payments and PayPal account payments.
 * You can also use sub-resources to get payment related details.
 *
 * Note that a PayPal Purchase Request looks exactly like a PayPal
 * Authorize request except that the 'intent' is set to 'sale' for
 * immediate payment.  This class takes advantage of that by
 * extending the RestAuthorizeRequest class and simply over-riding
 * the getData() function to set the intent to sale.
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
 *   $transaction = $gateway->purchase(array(
 *       'amount'        => '10.00',
 *       'currency'      => 'AUD',
 *       'description'   => 'This is a test purchase transaction.',
 *       'card'          => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * Direct credit card payment and related features are restricted in
 * some countries.
 * As of January 2015 these transactions are only supported in the UK
 * and in the USA.
 *
 * @link https://developer.paypal.com/docs/api/#create-a-payment
 * @see RestAuthorizeRequest
 */
class RestPurchaseRequest extends RestAuthorizeRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['intent'] = 'sale';
        return $data;
    }
}
