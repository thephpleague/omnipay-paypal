<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Default API version for requests.
     *
     * @see getVersion() to get the version.
     * @deprecated 3.0.0 getVersion() should be used instead.
     *
     * @var string
     */
    const API_VERSION = '85.0';

    protected $liveEndpoint = 'https://api-3t.paypal.com/nvp';
    protected $testEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * Operation for this request.
     *
     * @see getOperation() to get the operation.
     * @deprecated 3.0.0 getOperation() must be overridden.
     *
     * @var string
     */
    private $operation;

    /**
     * Get the operation for this request.
     *
     * @todo make this abstract to force override in v3.0.0
     *
     * @return string operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Get the API version of this request.
     *
     * @return string version
     */
    public function getVersion()
    {
        return static::API_VERSION;
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getSignature()
    {
        return $this->getParameter('signature');
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    public function getSubject()
    {
        return $this->getParameter('subject');
    }

    public function setSubject($value)
    {
        return $this->setParameter('subject', $value);
    }

    public function getSolutionType()
    {
        return $this->getParameter('solutionType');
    }

    public function setSolutionType($value)
    {
        return $this->setParameter('solutionType', $value);
    }

    public function getLandingPage()
    {
        return $this->getParameter('landingPage');
    }

    public function setLandingPage($value)
    {
        return $this->setParameter('landingPage', $value);
    }

    public function getHeaderImageUrl()
    {
        return $this->getParameter('headerImageUrl');
    }

    public function setHeaderImageUrl($value)
    {
        return $this->setParameter('headerImageUrl', $value);
    }

    public function getBrandName()
    {
        return $this->getParameter('brandName');
    }

    public function setBrandName($value)
    {
        return $this->setParameter('brandName', $value);
    }

    public function getNoShipping()
    {
        return $this->getParameter('noShipping');
    }

    public function setNoShipping($value)
    {
        return $this->setParameter('noShipping', $value);
    }

    public function getAllowNote()
    {
        return $this->getParameter('allowNote');
    }

    public function setAllowNote($value)
    {
        return $this->setParameter('allowNote', $value);
    }

    /**
     * Get the request data for this message.
     *
     * @return array request data
     */
    public function getData()
    {
        $this->validate('username', 'password');

        $operation = $this->getOperation();
        return $this->getBaseData($operation);
    }

    /**
     * Get the request data for this message.
     *
     * @see getData() to get the base data.
     * @deprecated 3.0.0 getData() must be used instead.
     *
     * @param  string  $operation  operation for this request
     * @return array               request data
     */
    protected function getBaseData($operation)
    {
        $this->operation = $operation;

        $data = array();

        // Operation
        $data['METHOD'] = $this->getOperation();
        $data['VERSION'] = $this->getVersion();

        // Credentials
        $data['USER'] = $this->getUsername();
        $data['PWD'] = $this->getPassword();
        $data['SIGNATURE'] = $this->getSignature();
        $data['SUBJECT'] = $this->getSubject();

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint().'?'.http_build_query($data, '', '&');
        $httpResponse = $this->httpClient->get($url)->send();

        file_put_contents('/tmp/paypal_' . $this->getOperation(), $httpResponse->getMessage());

        return $this->createResponse($httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Create a relevant response message.
     *
     * @param  array     $data  response data
     * @return Response         response message
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
