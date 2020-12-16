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

        if (isset($this->data['token_id'])) {
            // This would be present when dealing with a case where the user
            // authorize's their card within paypal. In this case the transactionReference
            // will be used during the redirect.
            return $this->data['token_id'];
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
            !empty($this->data['transactions'][0]['related_resources'][0]['sale']['transaction_fee'])) {
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

    /**
     * Get a string that will represent the stored card in future requests.
     *
     * If they have authorised the payment through submitting a card through Omnipay
     * this will be a reference to the card in the vault. If they have authorised the payment
     * through paypal this will be a reference to an approved billing agreement.
     */
    public function getCardReference()
    {
        if ($this->isPaypalApproval()) {
            if (isset($this->data['funding_instruments'])) {
                return $this->data['payer']['funding_instruments'][0]['billing']['billing_agreement_id'];
            }
            return false;
        }
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    public function isPaypalApproval()
    {
        if (!isset($this->data['payer']['payment_method'])) {
            return false;
        }
        return ($this->data['payer']['payment_method'] === 'paypal');
    }
}
