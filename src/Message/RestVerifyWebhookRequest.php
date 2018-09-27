<?php
/**
 * PayPal REST Verify Webhook Request
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal REST Verify Webhook Request
 *
 * Verify a webhook signature
 *
 * @link https://developer.paypal.com/docs/api/webhooks/#verify-webhook-signature
 */
class RestVerifyWebhookRequest extends AbstractRestRequest
{
    public function getAuthAlgo()
    {
        return $this->getParameter('authAlgo');
    }

    public function setAuthAlgo($value)
    {
        $this->setParameter('authAlgo', $value);
    }

    public function getCertUrl()
    {
        return $this->getParameter('certUrl');
    }

    public function setCertUrl($value)
    {
        $this->setParameter('certUrl', $value);
    }

    public function getTransmissionId()
    {
        return $this->getParameter('transmissionId');
    }

    public function setTransmissionId($value)
    {
        $this->setParameter('transmissionId', $value);
    }

    public function getTransmissionSig()
    {
        return $this->getParameter('transmissionSig');
    }

    public function setTransmissionSig($value)
    {
        $this->setParameter('transmissionSig', $value);
    }

    public function getTransmissionTime()
    {
        return $this->getParameter('transmissionTime');
    }

    public function setTransmissionTime($value)
    {
        $this->setParameter('transmissionTime', $value);
    }

    public function getWebhookId()
    {
        return $this->getParameter('webhookId');
    }

    public function setWebhookId($value)
    {
        $this->setParameter('webhookId', $value);
    }

    public function getWebhookEvent()
    {
        return $this->getParameter('webhookEvent');
    }

    public function setWebhookEvent(array $value)
    {
        $this->setParameter('webhookEvent', $value);
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/notifications/verify-webhook-signature';
    }

    public function getData()
    {
        $this->validate('authAlgo', 'certUrl', 'transmissionId', 'transmissionSig', 'transmissionTime', 'webhookId', 'webhookEvent');
        $data = array(
            'auth_algo' => $this->getAuthAlgo(),
            'cert_url' => $this->getCertUrl(),
            'transmission_id' => $this->getTransmissionId(),
            'transmission_sig' => $this->getTransmissionSig(),
            'transmission_time' => $this->getTransmissionTime(),
            'webhook_id' => $this->getWebhookId(),
            'webhook_event' => $this->getWebhookEvent()
        );

        return $data;
    }
}
