<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Fetch Further Details
 */

class FetchExpressCheckoutRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('GetExpressCheckoutDetails');

        $this->validate('transactionReference');

        $data['TOKEN'] = $this->getTransactionReference();

        $url = $this->getEndpoint()
                ."?USER={$data['USER']}"
                ."&PWD={$data['PWD']}&SIGNATURE={$data['SIGNATURE']}"
                ."&METHOD=GetExpressCheckoutDetails"
                ."&VERSION={$data['VERSION']}"
                ."&TOKEN={$data['TOKEN']}";

        parse_str(file_get_contents($url), $output);

        $data = array_merge($data, $output);

        return $data;
    }
}
