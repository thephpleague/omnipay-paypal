<?php


namespace Omnipay\PayPal\Message;


class RestCompletePurchaseRequest extends AbstractRestRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('transactionReference', 'payerId');

        $data = array(
            'payer_id' => $this->getPayerId()
        );

        return $data;
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/payment/' . $this->getTransactionReference() . '/execute';
    }
}
