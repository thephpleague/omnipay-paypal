<?php
/**
 * PayPal REST Capture Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Capture Request
 *
 * Use this resource to capture and process a previously created authorization.
 * To use this resource, the original payment call must have the intent set to
 * authorize.
 *
 * To capture payment, make a call to /v1/payments/authorization/{authorization_id}/capture
 * with the authorization ID in the URI along with an amount object. For a
 * partial capture, you can provide a lower amount. Additionally, you can explicitly
 * indicate a final capture (prevent future captures) by setting the is_final_capture
 * value to true.
 *
 * @link https://developer.paypal.com/docs/api/#capture-an-authorization
 */
class RestCaptureRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        return array(
            'amount' => array(
                'currency' => $this->getCurrency(),
                'total' => $this->getAmount(),
            ),
            'is_final_capture' => true,
        );
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/authorization/' . $this->getTransactionReference() . '/capture';
    }
}
