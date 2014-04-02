<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Authorize Request
 */
class ExpressAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('SetExpressCheckout');

        $this->validate('amount', 'returnUrl', 'cancelUrl');

        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmount();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();
        $data['PAYMENTREQUEST_0_NOTIFYURL'] = $this->getNotifyUrl();

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['RETURNURL'] = $this->getReturnUrl();
        $data['CANCELURL'] = $this->getCancelUrl();
        $data['HDRIMG'] = $this->getHeaderImageUrl();
        $data['BRANDNAME'] = $this->getBrandName();
        $data['NOSHIPPING'] = $this->getNoShipping();
        $data['ALLOWNOTE'] = $this->getAllowNote();

        $card = $this->getCard();
        if ($card) {
            $data['PAYMENTREQUEST_0_SHIPTONAME'] = $card->getName();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET'] = $card->getAddress1();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $card->getAddress2();
            $data['PAYMENTREQUEST_0_SHIPTOCITY'] = $card->getCity();
            $data['PAYMENTREQUEST_0_SHIPTOSTATE'] = $card->getState();
            $data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $card->getCountry();
            $data['PAYMENTREQUEST_0_SHIPTOZIP'] = $card->getPostcode();
            $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $card->getPhone();
            $data['EMAIL'] = $card->getEmail();
        }

        $itemData = $this->getItemData();
        $data = array_merge($data, $itemData);

        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new ExpressAuthorizeResponse($this, $data);
    }
}
