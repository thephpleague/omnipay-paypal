<?php

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;
use Omnipay\PayPal\Message\ProAuthorizeRequest;
use Omnipay\PayPal\Message\CaptureRequest;
use Omnipay\PayPal\Message\RefundRequest;

/**
 * PayPal Pro Class using REST API
 */
class RestGateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayPal REST';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId'     => '',
            'secret'       => '',
            'token'        => '',
            'testMode'     => false,
        );
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * @param bool $createIfNeeded [optional] - If there is not an active token present, should we create one?
     * @return string
     */
    public function getToken($createIfNeeded = true)
    {
        if ($createIfNeeded && !$this->hasToken()) {
            $response = $this->createToken()->send();
            if ($response->isSuccessful()) {
                $data = $response->getData();
                if (isset($data['access_token'])) {
                    $this->setToken($data['access_token']);
                    $this->setTokenExpires(time() + $data['expires_in']);
                }
            }
        }

        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getTokenExpires()
    {
        return $this->getParameter('tokenExpires');
    }

    public function setTokenExpires($value)
    {
        return $this->setParameter('tokenExpires', $value);
    }

    /**
     * Is there a bearer token and is it still valid?
     * @return bool
     */
    public function hasToken()
    {
        $token = $this->getParameter('token');
        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) $expires = strtotime($expires);
        return !empty($token) && time() < $expires;
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestAuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestAuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestPurchaseRequest', $parameters);
    }

//    public function capture(array $parameters = array())
//    {
////        return $this->createRequest('\Omnipay\PayPal\Message\CaptureRequest', $parameters);
//    }
//
//    public function refund(array $parameters = array())
//    {
////        return $this->createRequest('\Omnipay\PayPal\Message\RefundRequest', $parameters);
//    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestFetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestFetchTransactionRequest', $parameters);
    }

    /**
     * @return \Omnipay\PayPal\Message\RestTokenRequest
     */
    public function createToken()
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestTokenRequest', array());
    }

    /**
     * @param string $class
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\AbstractRestRequest
     */
    public function createRequest($class, array $parameters = array())
    {
        if (!$this->hasToken() && $class != '\Omnipay\PayPal\Message\RestTokenRequest') $this->getToken(true);
        return parent::createRequest($class, $parameters);
    }

}
