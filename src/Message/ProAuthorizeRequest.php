<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Pro Authorize Request
 */
class ProAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['METHOD'] = 'DoDirectPayment';
        $data['PAYMENTACTION'] = 'Authorization';
        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();
        $data['BUTTONSOURCE'] = $this->getButtonSource();

        // add credit card details
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['CREDITCARDTYPE'] = $this->getCard()->getBrand();
        $data['EXPDATE'] = $this->getCard()->getExpiryDate('mY');
        $data['STARTDATE'] = $this->getCard()->getStartDate('mY');
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
        $data['IPADDRESS'] = $this->getClientIp();
        $data['FIRSTNAME'] = $this->getCard()->getFirstName();
        $data['LASTNAME'] = $this->getCard()->getLastName();
        $data['EMAIL'] = $this->getCard()->getEmail();
        $data['STREET'] = $this->getCard()->getAddress1();
        $data['STREET2'] = $this->getCard()->getAddress2();
        $data['CITY'] = $this->getCard()->getCity();
        $data['STATE'] = $this->getCard()->getState();
        $data['ZIP'] = $this->getCard()->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($this->getCard()->getCountry());

        return $data;
    }
}
