<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Complete Authorize Request
 */
class ExpressCompleteAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();
        $data['METHOD'] = 'DoExpressCheckoutPayment';
        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmount();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();
        $data['PAYMENTREQUEST_0_NOTIFYURL'] = $this->getNotifyUrl();

        $items = $this->getItems();
        if ($items) {
            foreach ($items as $n => $item) {
                $data["L_PAYMENTREQUEST_0_NAME$n"] = $item->getName();
                $data["L_PAYMENTREQUEST_0_DESC$n"] = $item->getDescription();
                $data["L_PAYMENTREQUEST_0_QTY$n"] = $item->getQuantity();
                $data["L_PAYMENTREQUEST_0_AMT$n"] = $this->formatCurrency($item->getPrice());
            }
        }

        $data['TOKEN'] = $this->httpRequest->query->get('token');
        $data['PAYERID'] = $this->httpRequest->query->get('PayerID');

        $data = array_merge($data, $this->getItemData());

        return $data;
    }
}
