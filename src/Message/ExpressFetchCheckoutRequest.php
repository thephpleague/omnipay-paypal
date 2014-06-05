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
        //Allow override, useful for negative testing, GetExpressCheckoutDetails uses TOKEN
        if (!is_null($this->getToken())) {
            $data['TOKEN'] = $this->getToken();
        } else {
            $data['TOKEN'] = $this->httpRequest->query->get('token');
        }

        return $data;
    }
}
