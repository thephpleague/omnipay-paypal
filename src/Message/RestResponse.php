<?php
/**
 * PayPal REST Response
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal REST Response
 */
class RestResponse extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        return empty($this->data['error']) && $this->getCode() < 400;
    }

    public function getTransactionReference()
    {
        // This is usually correct for payments, authorizations, etc
        if (!empty($this->data['transactions']) && !empty($this->data['transactions'][0]['related_resources'])) {
            foreach (array('sale', 'authorization') as $type) {
                if (!empty($this->data['transactions'][0]['related_resources'][0][$type])) {
                    return $this->data['transactions'][0]['related_resources'][0][$type]['id'];
                }
            }
        }

        // This is a fallback, but is correct for fetch transaction and possibly others
        if (!empty($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }

    /*
     * The fee taken by PayPal for the transaction. Available from the Sale
     * object in RelatedResources only once a transaction has been completed
     * and funds have been received by merchant.
     *
     *  https://developer.paypal.com/docs/api/payments/v1/#definition-sale
     *
     *
     * There may be a 'fee' field associated with amount details object but
     * this seems to pertain to a handling_fee charged to client rather than
     * the PayPal processing fee.
     *
     *  https://developer.paypal.com/docs/api/payments/v1/#definition-details
     */
    public function getProcessorFeeAmount()
    {
        if (!empty($this->data['transactions']) &&
            !empty($this->data['transactions'][0]['related_resources']) &&
            !empty($this->data['transactions'][0]['related_resources'][0]['sale']) &&
            !empty($this->data['transactions'][0]['related_resources'][0]['sale']['transaction_fee']))
        {
            return $this->data['transactions'][0]['related_resources'][0]['sale']['transaction_fee']['value'];
        }

        return null;
    }

    public function getMessage()
    {
        if (isset($this->data['error_description'])) {
            return $this->data['error_description'];
        }

        if (isset($this->data['message'])) {
            return $this->data['message'];
        }
        
        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }

    public function getCardReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }
}
