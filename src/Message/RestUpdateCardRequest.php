<?php
/**
 * PayPal REST Update Card Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Update Card Request
 *
 * PayPal offers merchants a /vault API to store sensitive details
 * like credit card related details.
 *
 * You can currently use the /vault API to store credit card details
 * with PayPal instead of storing them on your own server. After storing
 * a credit card, you can then pass the credit card id instead of the
 * related credit card details to complete a payment.
 *
 * Direct credit card payment and related features are restricted in
 * some countries.
 * As of January 2015 these transactions are only supported in the UK
 * and in the USA.
 *
 * TODO: This class does not work.  See issue 41 on github
 * https://github.com/thephpleague/omnipay-paypal/issues/41
 *
 * @link https://developer.paypal.com/docs/api/#vault
 * @link https://developer.paypal.com/docs/api/#update-a-stored-credit-card
 * @link http://bit.ly/1wUQ33R
 * @see RestCreateCardRequest
 */
class RestUpdateCardRequest extends RestCreateCardRequest
{
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/vault/credit-card/' . $this->getCardReference();
    }

    public function getHttpMethod()
    {
        return 'PATCH';
    }
}
