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
        $data['AMT'] = $this->getAmount()->getFormatted();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();

        // add credit card details
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['CREDITCARDTYPE'] = $this->getCard()->getBrand();
        $data['EXPDATE'] = $this->getCard()->getExpiryDate('mY');
        $data['STARTDATE'] = $this->getCard()->getStartDate('mY');
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
        $data['IPADDRESS'] = $this->getClientIp();
        $data['FIRSTNAME'] = $this->getCustomer()->getFirstName();
        $data['LASTNAME'] = $this->getCustomer()->getLastName();
        $data['EMAIL'] = $this->getCustomer()->getEmail();
        $data['STREET'] = $this->getCustomer()->getAddress1();
        $data['STREET2'] = $this->getCustomer()->getAddress2();
        $data['CITY'] = $this->getCustomer()->getCity();
        $data['STATE'] = $this->getCustomer()->getState();
        $data['ZIP'] = $this->getCustomer()->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($this->getCustomer()->getCountry());

        return $data;
    }
}
