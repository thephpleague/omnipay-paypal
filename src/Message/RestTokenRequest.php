<?php
/**
 * PayPal REST Token Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Token Request
 *
 * With each API call, you’ll need to set request headers, including
 * an OAuth 2.0 access token. Get an access token by using the OAuth
 * 2.0 client_credentials token grant type with your clientId:secret
 * as your Basic Auth credentials.
 *
 * @link https://developer.paypal.com/docs/integration/direct/make-your-first-call/
 * @link https://developer.paypal.com/docs/api/#authentication--headers
 */
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
        // Empty response body should be parsed also as and empty array
        $body = $httpResponse->getBody(true);
        $jsonToArrayResponse = !empty($body) ? $httpResponse->json() : array();
        return $this->response = new RestResponse($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }
}
