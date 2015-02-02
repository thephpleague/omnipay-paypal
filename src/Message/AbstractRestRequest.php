<?php
/**
 * PayPal Abstract REST Request
 */

namespace Omnipay\PayPal\Message;

use Guzzle\Http\EntityBody;

/**
 * PayPal Abstract REST Request
 *
 * This class forms the base class for PayPal REST requests via the PayPal REST APIs.
 *
 * A complete REST operation is formed by combining an HTTP method (or “verb”) with
 * the full URI to the resource you’re addressing. For example, here is the operation
 * to create a new payment:
 *
 * <code>
 * POST https://api.paypal.com/v1/payments/payment
 * </code>
 *
 * To create a complete request, combine the operation with the appropriate HTTP headers
 * and any required JSON payload.
 *
 * @link https://developer.paypal.com/docs/api/
 * @link https://devtools-paypal.com/integrationwizard/
 * @link http://paypal.github.io/sdk/
 * @see Omnipay\PayPal\RestGateway
 */
abstract class AbstractRestRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    /**
     * Sandbox Endpoint URL
     *
     * The PayPal REST APIs are supported in two environments. Use the Sandbox environment
     * for testing purposes, then move to the live environment for production processing.
     * When testing, generate an access token with your test credentials to make calls to
     * the Sandbox URIs. When you’re set to go live, use the live credentials assigned to
     * your app to generate a new access token to be used with the live URIs.
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://api.sandbox.paypal.com';

    /**
     * Live Endpoint URL
     *
     * When you’re set to go live, use the live credentials assigned to
     * your app to generate a new access token to be used with the live URIs.
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://api.paypal.com';

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

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
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

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() == 'GET') {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                array(
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-type'  => 'application/json',
                )
            );
        } else {
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
        }
        
        // Might be useful to have some debug code here, PayPal especially can be
        // a bit fussy about data formats and ordering.  Perhaps hook to whatever
        // logging engine is being used.
        echo "Data == " . json_encode($data) . "\n";

        $httpResponse = $httpRequest->send();

        return $this->response = new RestResponse($this, $httpResponse->json(), $httpResponse->getStatusCode());
    }
}
