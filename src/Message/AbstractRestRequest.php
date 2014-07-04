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

//    public function getSubject()
//    {
//        return $this->getParameter('subject');
//    }
//
//    public function setSubject($value)
//    {
//        return $this->setParameter('subject', $value);
//    }
//
//    public function getSolutionType()
//    {
//        return $this->getParameter('solutionType');
//    }
//
//    public function setSolutionType($value)
//    {
//        return $this->setParameter('solutionType', $value);
//    }
//
//    public function getLandingPage()
//    {
//        return $this->getParameter('landingPage');
//    }
//
//    public function setLandingPage($value)
//    {
//        return $this->setParameter('landingPage', $value);
//    }
//
//    public function getHeaderImageUrl()
//    {
//        return $this->getParameter('headerImageUrl');
//    }
//
//    public function setHeaderImageUrl($value)
//    {
//        return $this->setParameter('headerImageUrl', $value);
//    }
//
//    public function getBrandName()
//    {
//        return $this->getParameter('brandName');
//    }
//
//    public function setBrandName($value)
//    {
//        return $this->setParameter('brandName', $value);
//    }
//
//    public function getNoShipping()
//    {
//        return $this->getParameter('noShipping');
//    }
//
//    public function setNoShipping($value)
//    {
//        return $this->setParameter('noShipping', $value);
//    }
//
//    public function getAllowNote()
//    {
//        return $this->getParameter('allowNote');
//    }
//
//    public function setAllowNote($value)
//    {
//        return $this->setParameter('allowNote', $value);
//    }
//
//    public function getAddressOverride()
//    {
//        return $this->getParameter('addressOverride');
//    }
//
//    public function setAddressOverride($value)
//    {
//        return $this->setParameter('addressOverride', $value);
//    }

//    protected function getBaseData()
//    {
//        $data = array();
//        $data['VERSION'] = static::API_VERSION;
//        $data['USER'] = $this->getUsername();
//        $data['PWD'] = $this->getPassword();
//        $data['SIGNATURE'] = $this->getSignature();
//        $data['SUBJECT'] = $this->getSubject();
//
//        return $data;
//    }

//    protected function getItemData()
//    {
//        $data = array();
//        $items = $this->getItems();
//        if ($items) {
//            foreach ($items as $n => $item) {
//                $data["L_PAYMENTREQUEST_0_NAME$n"] = $item->getName();
//                $data["L_PAYMENTREQUEST_0_DESC$n"] = $item->getDescription();
//                $data["L_PAYMENTREQUEST_0_QTY$n"] = $item->getQuantity();
//                $data["L_PAYMENTREQUEST_0_AMT$n"] = $this->formatCurrency($item->getPrice());
//            }
//        }
//
//        return $data;
//    }

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
//                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Content-type' => 'application/json',
            ),
            json_encode($data)
        );

        $httpResponse = $httpRequest->send();
        \Debug::log(print_r($httpResponse, true));

        return $this->response = new RestResponse($this, $httpResponse->json(), $httpResponse->getStatusCode());
    }


}
