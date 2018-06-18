<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PayPal\Support\InstantUpdateApi\ShippingOption;
use Omnipay\PayPal\Support\InstantUpdateApi\BillingAgreement;

/**
 * PayPal Express Capture Request
 */
class ExpressCaptureRequest extends ExpressCompletePurchaseRequest
{
    public function getData()
    {

        $data = parent::getData();
        $data['TOKEN'] = $this->getTransactionReference();
        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new ExpressCaptureResponse($this, $data);
    }
}
