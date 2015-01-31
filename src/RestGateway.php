<?php
/**
 * PayPal Pro Class using REST API
 */

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;
use Omnipay\PayPal\Message\ProAuthorizeRequest;
use Omnipay\PayPal\Message\CaptureRequest;
use Omnipay\PayPal\Message\RefundRequest;

/**
 * PayPal Pro Class using REST API
 *
 * This class forms the gateway class for PayPal REST requests via the PayPal REST APIs.
 *
 * The PayPal API uses HTTP verbs and a RESTful endpoint structure. OAuth 2.0 is used
 * as the API Authorization framework. Request and response payloads are formatted as JSON.
 *
 * The PayPal REST APIs are supported in two environments. Use the Sandbox environment
 * for testing purposes, then move to the live environment for production processing.
 * When testing, generate an access token with your test credentials to make calls to
 * the Sandbox URIs. When you’re set to go live, use the live credentials assigned to
 * your app to generate a new access token to be used with the live URIs.
 *
 * With each API call, you’ll need to set request headers, including an OAuth 2.0
 * access token. Get an access token by using the OAuth 2.0 client_credentials token
 * grant type with your clientId:secret as your Basic Auth credentials. For more
 * information, see Make your first call (link).  This class sets all of the headers
 * associated with the API call for you, including making preliminary calls to create
 * or update the OAuth 2.0 access token before each call you make, if required.  All
 * you need to do is provide the clientId and secret when you initialize the gateway,
 * or use the set*() calls to set them after creating the gateway object.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal RestGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('RestGateway');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'clientId' => 'MyPayPalClientId',
 *       'secret'   => 'MyPayPalSecret',
 *       'testMode' => false, // Or true to use the sandbox
 *   );
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $card = new CreditCard(array(
 *               'firstName' => 'Example',
 *               'lastName' => 'User',
 *               'number' => '4111111111111111',
 *               'expiryMonth' => '12',
 *               'expiryYear' => '2016',
 *               'cvv' => '123',
 *           ));
 *
 *   // Do an authorisation transaction on the gateway
 *   if ($gateway->supportsAuthorize()) {
 *       $gateway->authorize(array(
 *           'amount' => '10.00',
 *           'card'   => $card,
 *      ));
 *   } else {
 *       throw new \Exception('Gateway does not support authorize()');
 *   }
 * </code>
 *
 * @link https://developer.paypal.com/docs/api/
 * @link https://devtools-paypal.com/integrationwizard/
 * @link http://paypal.github.io/sdk/
 * @link https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/
 * @link https://developer.paypal.com/docs/faq/
 * @link https://developer.paypal.com/docs/integration/direct/make-your-first-call/
 * @link https://developer.paypal.com/docs/integration/web/accept-paypal-payment/
 * @link https://developer.paypal.com/docs/api/#authentication--headers
 * @see Omnipay\PayPal\Message\AbstractRestRequest
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

    //
    // Tokens -- methods to set up, store and retrieve the OAuth 2.0 access token.
    //
    // @link https://developer.paypal.com/docs/api/#authentication--headers
    //

    /**
     * Get OAuth 2.0 client ID for the access token.
     * 
     * Get an access token by using the OAuth 2.0 client_credentials
     * token grant type with your clientId:secret as your Basic Auth
     * credentials.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * Set OAuth 2.0 client ID for the access token.
     * 
     * Get an access token by using the OAuth 2.0 client_credentials
     * token grant type with your clientId:secret as your Basic Auth
     * credentials.
     *
     * @param string $value
     * @return RestGateway provides a fluent interface
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Get OAuth 2.0 secret for the access token.
     * 
     * Get an access token by using the OAuth 2.0 client_credentials
     * token grant type with your clientId:secret as your Basic Auth
     * credentials.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * Set OAuth 2.0 secret for the access token.
     * 
     * Get an access token by using the OAuth 2.0 client_credentials
     * token grant type with your clientId:secret as your Basic Auth
     * credentials.
     *
     * @param string $value
     * @return RestGateway provides a fluent interface
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * Get OAuth 2.0 access token.
     *
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

    /**
     * Create OAuth 2.0 access token request.
     *
     * @return \Omnipay\PayPal\Message\RestTokenRequest
     */
    public function createToken()
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestTokenRequest', array());
    }

    /**
     * Set OAuth 2.0 access token.
     * 
     * @param string $value
     * @return RestGateway provides a fluent interface
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * Get OAuth 2.0 access token expiry time.
     * 
     * @return integer
     */
    public function getTokenExpires()
    {
        return $this->getParameter('tokenExpires');
    }

    /**
     * Set OAuth 2.0 access token expiry time.
     * 
     * @param integer $value
     * @return RestGateway provides a fluent interface
     */
    public function setTokenExpires($value)
    {
        return $this->setParameter('tokenExpires', $value);
    }

    /**
     * Is there a bearer token and is it still valid?
     *
     * @return bool
     */
    public function hasToken()
    {
        $token = $this->getParameter('token');

        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) {
            $expires = strtotime($expires);
        }

        return !empty($token) && time() < $expires;
    }

    //
    // Payments -- Create payments or get details of one or more payments.
    //
    // @link https://developer.paypal.com/docs/api/#payments
    //

    /**
     * Create a purchase request.
     *
     * PayPal provides various payment related operations using the /payment
     * resource and related sub-resources. Use payment for direct credit card
     * payments and PayPal account payments. You can also use sub-resources
     * to get payment related details.
     *
     * @link https://developer.paypal.com/docs/api/#create-a-payment
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestPurchaseRequest', $parameters);
    }

    // TODO: Execute an approved PayPal payment https://developer.paypal.com/docs/api/#execute-an-approved-paypal-payment
    // TODO: Look up a payment resource https://developer.paypal.com/docs/api/#look-up-a-payment-resource
    // TODO: Update a payment resource https://developer.paypal.com/docs/api/#update-a-payment-resource
    // TODO: List payment resources https://developer.paypal.com/docs/api/#list-payment-resources

    //
    // Authorizations -- Capture, reauthorize, void and look up authorizations.
    //
    // @link https://developer.paypal.com/docs/api/#authorizations
    // @link https://developer.paypal.com/docs/integration/direct/capture-payment/
    //

    /**
     * Create an authorization request.
     *
     * To collect payment at a later time, first authorize a payment using the /payment resource.
     * You can then capture the payment to complete the sale and collect payment.
     *
     * @link https://developer.paypal.com/docs/integration/direct/capture-payment/#authorize-the-payment
     * @link https://developer.paypal.com/docs/api/#authorizations
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestAuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestAuthorizeRequest', $parameters);
    }

    /**
     * Capture an authorization.
     *
     * Use this resource to capture and process a previously created authorization.
     * To use this resource, the original payment call must have the intent set to
     * authorize.
     *
     * @link https://developer.paypal.com/docs/api/#capture-an-authorization
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestCaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestCaptureRequest', $parameters);
    }

    //
    // Sale Transactions -- Get and refund completed payments (sale transactions).
    // @link https://developer.paypal.com/docs/api/#sale-transactions
    //

    /**
     * Fetch a Sale Transaction
     *
     * To get details about completed payments (sale transaction) created by a payment request
     * or to refund a direct sale transaction, PayPal provides the /sale resource and related
     * sub-resources.
     *
     * @link https://developer.paypal.com/docs/api/#sale-transactions
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestFetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestFetchTransactionRequest', $parameters);
    }

    /**
     * Refund a Sale Transaction
     *
     * To get details about completed payments (sale transaction) created by a payment request
     * or to refund a direct sale transaction, PayPal provides the /sale resource and related
     * sub-resources.
     *
     * @link https://developer.paypal.com/docs/api/#sale-transactions
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestRefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestRefundRequest', $parameters);
    }

    //
    // Vault: Store customer credit cards securely.
    //
    // @link https://developer.paypal.com/docs/api/#vault
    //

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestCreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestCreateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestCreateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestUpdateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\RestDeleteCardRequest
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestDeleteCardRequest', $parameters);
    }

    /**
     * Create Request
     *
     * This overrides the parent createRequest function ensuring that the OAuth
     * 2.0 access token is passed along with the request data -- unless the
     * request is a RestTokenRequest in which case no token is needed.  If no
     * token is available then a new one is created (e.g. if there has been no
     * token request or the current token has expired).
     *
     * @param string $class
     * @param array $parameters
     * @return \Omnipay\PayPal\Message\AbstractRestRequest
     */
    public function createRequest($class, array $parameters = array())
    {
        if (!$this->hasToken() && $class != '\Omnipay\PayPal\Message\RestTokenRequest') {
            // This will set the internal token parameter which the parent
            // createRequest will find when it calls getParameters().
            $this->getToken(true);
        }

        return parent::createRequest($class, $parameters);
    }
}
