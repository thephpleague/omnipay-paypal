<?php

namespace Omnipay\PayPal\Message;

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
