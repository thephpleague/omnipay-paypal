<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = '85.0';

    protected $liveEndpoint = 'https://api-3t.paypal.com/nvp';
    protected $testEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';

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

    public function getLogoImageUrl()
    {
        return $this->getParameter('logoImageUrl');
    }

    public function setLogoImageUrl($value)
    {
        return $this->setParameter('logoImageUrl', $value);
    }

    public function getBorderColor()
    {
        return $this->getParameter('borderColor');
    }

    public function setBorderColor($value)
    {
        return $this->setParameter('borderColor', $value);
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

    public function getAddressOverride()
    {
        return $this->getParameter('addressOverride');
    }

    public function setAddressOverride($value)
    {
        return $this->setParameter('addressOverride', $value);
    }

    public function getTaxAmount()
    {
        return $this->getParameter('taxAmount');
    }

    public function setTaxAmount($value)
    {
        return $this->setParameter('taxAmount', $value);
    }

    public function getShippingAmount()
    {
        return $this->getParameter('shippingAmount');
    }

    public function setShippingAmount($value)
    {
        return $this->setParameter('shippingAmount', $value);
    }

    public function getHandlingAmount()
    {
        return $this->getParameter('handlingAmount');
    }

    public function setHandlingAmount($value)
    {
        return $this->setParameter('handlingAmount', $value);
    }

    public function getShippingDiscount()
    {
        return $this->getParameter('shippingDiscount');
    }

    public function setShippingDiscount($value)
    {
        return $this->setParameter('shippingDiscount', $value);
    }

    public function getInsuranceAmount()
    {
        return $this->getParameter('insuranceAmount');
    }

    public function setInsuranceAmount($value)
    {
        return $this->setParameter('insuranceAmount', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['VERSION'] = static::API_VERSION;
        $data['USER'] = $this->getUsername();
        $data['PWD'] = $this->getPassword();
        $data['SIGNATURE'] = $this->getSignature();
        $data['SUBJECT'] = $this->getSubject();

        return $data;
    }

    protected function getItemData()
    {
        $data = array();
        $items = $this->getItems();
        if ($items) {
            $data["PAYMENTREQUEST_0_ITEMAMT"] = 0;
            foreach ($items as $n => $item) {
                $data["L_PAYMENTREQUEST_0_NAME$n"] = $item->getName();
                $data["L_PAYMENTREQUEST_0_DESC$n"] = $item->getDescription();
                $data["L_PAYMENTREQUEST_0_QTY$n"] = $item->getQuantity();
                $data["L_PAYMENTREQUEST_0_AMT$n"] = $this->formatCurrency($item->getPrice());

                $data["PAYMENTREQUEST_0_ITEMAMT"] += $item->getQuantity() * $this->formatCurrency($item->getPrice());
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint().'?'.http_build_query($data, '', '&');
        $httpResponse = $this->httpClient->get($url)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
