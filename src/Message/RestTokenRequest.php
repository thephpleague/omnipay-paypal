<?php
/**
 * PayPal REST Token Request
 */

namespace Omnipay\PayPal\Message;

use League\Omnipay\Common\Http\Factory;

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
        $httpRequest = Factory::createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array(
                'Accept' => 'application/json',
                'Authorization' => 'Basic '. base64_encode($this->getClientId().':'.$this->getSecret())
            ),
            json_encode($data)
        );

        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        // Empty response body should be parsed also as and empty array
        $body = $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        return $this->response = new RestResponse($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }
}
