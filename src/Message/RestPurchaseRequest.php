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
 * @link https://developer.paypal.com/docs/api/#create-a-payment
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
