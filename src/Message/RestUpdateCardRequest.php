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
 * TODO: As of January 2015 this update card request does not work.  A
 * support request has been put to PayPal and there is also a stackoverflow
 * ticket about this:
 *
 * @link http://stackoverflow.com/questions/20858910/paypal-rest-api-update-a-stored-credit-card
 * @link https://ppmts.custhelp.com/app/account/questions/detail/i_id/1081166
 *
 * @link https://developer.paypal.com/docs/api/#vault
 * @link https://developer.paypal.com/docs/api/#update-a-stored-credit-card
 * @link http://bit.ly/1wUQ33R
 * @see RestCreateCardRequest
 */
class RestUpdateCardRequest extends RestCreateCardRequest
{
    public function getData()
    {
        $data = parent::getData();
        
        // Reformat the data array and add the additional fields that
        // are required to complete an update request.  Assume that all
        // of the card data are being replaced.
        $newdata = array();
        $newdata['op'] = 'replace';
        $newdata['path'] = '/';
        $newdata['value'] = $data;

        return $newdata;
    }

    public function getHttpMethod()
    {
        return 'PATCH';
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/' . $this->getCardReference();
    }
}
