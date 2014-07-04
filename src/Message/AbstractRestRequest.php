<?php

namespace Omnipay\PayPal\Message;
use Guzzle\Http\EntityBody;

/**
 * PayPal Abstract Request
 */
abstract class AbstractRestRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    protected $liveEndpoint = 'https://api.paypal.com';
    protected $testEndpoint = 'https://api.sandbox.paypal.com';

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

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getEndpoint()
    {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        return $base . '/' . self::API_VERSION;
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array(
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Content-type'  => 'application/json',
            ),
            json_encode($data)
        );

        $httpResponse = $httpRequest->send();

        return $this->response = new RestResponse($this, $httpResponse->json(), $httpResponse->getStatusCode());
    }

}
