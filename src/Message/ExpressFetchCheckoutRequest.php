<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Fetch Checkout Details Request
 */
class ExpressFetchCheckoutRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate();

        $data = $this->getBaseData();
        $data['METHOD'] = 'GetExpressCheckoutDetails';
        $data['TOKEN'] = $this->httpRequest->query->get('token');

        return $data;
    }
}
