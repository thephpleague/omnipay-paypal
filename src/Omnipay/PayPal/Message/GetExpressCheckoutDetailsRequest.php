<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Get Express Checkout Details Request
 *
 * @author Joao Dias <joao.dias@cherrygroup.com>
 * @copyright 2014 Cherry Ltd.
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 112.0 PayPal Merchant API
 */
class GetExpressCheckoutDetailsRequest extends AbstractRequest
{
    public function getOperation()
    {
        return 'GetExpressCheckoutDetails';
    }

    public function getVersion()
    {
        return '112.0';
    }

    /**
     * Get the timestamped token, the value of which was returned by SetExpressCheckout
     * response.
     *
     * @return string token
     */
    public function getToken()
    {
        return $this->getParameter('token');
    }

    /**
     * Set the timestamped token, the value of which was returned by SetExpressCheckout
     * response.
     *
     * @param  $value token
     * @return $this
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getData()
    {
        $data = parent::getData();

        $this->validate('token');

        $data['TOKEN'] = $this->getToken();

        return $data;
    }

    /**
     * @return GetExpressCheckoutDetailsResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new GetExpressCheckoutDetailsResponse($this, $data);
    }
}
