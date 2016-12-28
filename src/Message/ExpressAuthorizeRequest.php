<?php

namespace Omnipay\PayPal\Message;

use League\Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PayPal\Support\InstantUpdateApi\ShippingOption;

/**
 * PayPal Express Authorize Request
 */
class ExpressAuthorizeRequest extends AbstractRequest
{

    const DEFAULT_CALLBACK_TIMEOUT = 5;

    public function setCallback($callback)
    {
        return $this->setParameter('callback', $callback);
    }

    public function getCallback()
    {
        return $this->getParameter('callback');
    }

    public function setCallbackTimeout($callbackTimeout)
    {
        return $this->setParameter('callbackTimeout', $callbackTimeout);
    }

    public function getCallbackTimeout()
    {
        return $this->getParameter('callbackTimeout');
    }

    /**
     * @param ShippingOption[] $data
     */
    public function setShippingOptions($data)
    {
        $this->setParameter('shippingOptions', $data);
    }

    /**
     * @return ShippingOption[]
     */
    public function getShippingOptions()
    {
        return $this->getParameter('shippingOptions');
    }

    protected function validateCallback()
    {
        $callback = $this->getCallback();

        if (!empty($callback)) {
            $shippingOptions = $this->getShippingOptions();

            if (empty($shippingOptions)) {
                throw new InvalidRequestException(
                    'When setting a callback for the Instant Update API you must set shipping options'
                );
            } else {
                $hasDefault = false;
                foreach ($shippingOptions as $shippingOption) {
                    if ($shippingOption->isDefault()) {
                        $hasDefault = true;
                        continue;
                    }
                }

                if (!$hasDefault) {
                    throw new InvalidRequestException(
                        'One of the supplied shipping options must be set as default'
                    );
                }
            }
        }
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl', 'cancelUrl');
        $this->validateCallback();

        $data = $this->getBaseData();
        $data['METHOD'] = 'SetExpressCheckout';
        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmount()->getFormatted();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['RETURNURL'] = $this->getReturnUrl();
        $data['CANCELURL'] = $this->getCancelUrl();
        $data['HDRIMG'] = $this->getHeaderImageUrl();
        $data['BRANDNAME'] = $this->getBrandName();
        $data['NOSHIPPING'] = $this->getNoShipping();
        $data['ALLOWNOTE'] = $this->getAllowNote();
        $data['ADDROVERRIDE'] = $this->getAddressOverride();
        $data['LOGOIMG'] = $this->getLogoImageUrl();
        $data['CARTBORDERCOLOR'] = $this->getBorderColor();
        $data['LOCALECODE'] = $this->getLocaleCode();
        $data['CUSTOMERSERVICENUMBER'] = $this->getCustomerServiceNumber();

        $callback = $this->getCallback();

        if (!empty($callback)) {
            $data['CALLBACK'] = $callback;
            // callback timeout MUST be included and > 0
            $timeout = $this->getCallbackTimeout();

            $data['CALLBACKTIMEOUT'] = $timeout > 0 ? $timeout : self::DEFAULT_CALLBACK_TIMEOUT;

            // if you're using a callback you MUST set shipping option(s)
            $shippingOptions = $this->getShippingOptions();

            if (!empty($shippingOptions)) {
                foreach ($shippingOptions as $index => $shipping) {
                    $data['L_SHIPPINGOPTIONNAME' . $index] = $shipping->getName();
                    $data['L_SHIPPINGOPTIONAMOUNT' . $index] = number_format($shipping->getAmount(), 2);
                    $data['L_SHIPPINGOPTIONISDEFAULT' . $index] = $shipping->isDefault() ? '1' : '0';

                    if ($shipping->hasLabel()) {
                        $data['L_SHIPPINGOPTIONLABEL' . $index] = $shipping->getLabel();
                    }
                }
            }
        }

        $data['MAXAMT'] = $this->getMaxAmount();
        $data['PAYMENTREQUEST_0_TAXAMT'] = $this->getTaxAmount();
        $data['PAYMENTREQUEST_0_SHIPPINGAMT'] = $this->getShippingAmount();
        $data['PAYMENTREQUEST_0_HANDLINGAMT'] = $this->getHandlingAmount();
        $data['PAYMENTREQUEST_0_SHIPDISCAMT'] = $this->getShippingDiscount();
        $data['PAYMENTREQUEST_0_INSURANCEAMT'] = $this->getInsuranceAmount();
        $data['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'] = $this->getSellerPaypalAccountId();

        $customer = $this->getCustomer();
        if ($customer) {
            $data['PAYMENTREQUEST_0_SHIPTONAME'] = $customer->getName();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET'] = $customer->getAddress1();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $customer->getAddress2();
            $data['PAYMENTREQUEST_0_SHIPTOCITY'] = $customer->getCity();
            $data['PAYMENTREQUEST_0_SHIPTOSTATE'] = $customer->getState();
            $data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $customer->getCountry();
            $data['PAYMENTREQUEST_0_SHIPTOZIP'] = $customer->getPostcode();
            $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $customer->getPhone();
            $data['EMAIL'] = $customer->getEmail();
        }


        $data = array_merge($data, $this->getItemData());

        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new ExpressAuthorizeResponse($this, $data);
    }
}
