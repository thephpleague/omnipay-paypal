<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayPal Express Authorize Response
 */
class ExpressAuthorizeResponse extends Response implements RedirectResponseInterface
{
    protected $liveCheckoutEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return isset($this->data['ACK']) && in_array($this->data['ACK'], array('Success', 'SuccessWithWarning'));
    }

    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint().'?'.http_build_query($this->getRedirectQueryParameters(), '', '&');
    }

    public function getTransactionReference()
    {
        return isset($this->data['TOKEN']) ? $this->data['TOKEN'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    protected function getRedirectQueryParameters()
    {
        return array(
            'cmd' => '_express-checkout',
            'useraction' => 'commit',
            'token' => $this->getTransactionReference(),
        );
    }

    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testCheckoutEndpoint : $this->liveCheckoutEndpoint;
    }
}
