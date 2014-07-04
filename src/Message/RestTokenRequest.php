<?php

namespace Omnipay\PayPal\Message;

class RestTokenRequest extends AbstractRestRequest
{
    public function getData()
    {
        return array('grant_type' => 'client_credentials');
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/oauth2/token';
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
            array('Accept' => 'application/json'),
            $data
        );

        $httpResponse = $httpRequest->setAuth($this->getClientId(), $this->getSecret())->send();

        return $this->response = new RestResponse($this, $httpResponse->json(), $httpResponse->getStatusCode());
    }
}