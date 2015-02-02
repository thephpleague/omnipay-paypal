<?php
/**
 * PayPal REST Response
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayPal REST Response
 */
class RestResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;
    protected $liveCheckoutEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

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

    //    
    // Redirect functions -- only used when payment_method = paypal in the request.
    //
    
    public function isRedirect() {
        if (! empty($this->data['links'])) {
            foreach ($this->data['links'] as $key => $val) {
                if ($val['rel'] = 'approval_url') {
                    return true;
                }
            }
        }
        
        return false;
    }

    public function getRedirectUrl()
    {
        if (! empty($this->data['links'])) {
            foreach ($this->data['links'] as $key => $val) {
                if ($val['rel'] == 'approval_url') {
                    return $val['href'];
                }
            }
        }

        return null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testCheckoutEndpoint : $this->liveCheckoutEndpoint;
    }
}
