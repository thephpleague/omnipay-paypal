<?php

namespace Omnipay\PayPal\Message;

/**
 * https://developer.paypal.com/docs/api/webhooks/#verify-webhook-signature
 */
final class RestVerifyWebhookSignatureRequest extends AbstractRestRequest
{
    /**
     * @return string
     */
    public function getAuthAlgo()
    {
        return $this->getParameter('auth_algo');
    }

    /**
     * @return string
     */
    public function getCertUrl()
    {
        return $this->getParameter('cert_url');
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [
            'transmission_id' => $this->getTransmissionId(),
            'auth_algo' => $this->getAuthAlgo(),
            'cert_url' => $this->getCertUrl(),
            'transmission_sig' => $this->getTransmissionSig(),
            'transmission_time' => $this->getTransmissionTime(),
            'webhook_event' => $this->getWebhookEvent(),
            'webhook_id' => $this->getWebhookId(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint()
    {
        return parent::getEndpoint().'/notifications/verify-webhook-signature';
    }

    /**
     * @return string
     */
    public function getTransmissionId()
    {
        return $this->getParameter('transmission_id');
    }

    /**
     * @return string
     */
    public function getTransmissionSig()
    {
        return $this->getParameter('transmission_sig');
    }

    /**
     * @return string
     */
    public function getTransmissionTime()
    {
        return $this->getParameter('transmission_time');
    }

    /**
     * @return string
     */
    public function getWebhookEvent()
    {
        return $this->getParameter('webhook_event');
    }

    /**
     * @return string
     */
    public function getWebhookId()
    {
        return $this->getParameter('webhook_id');
    }

    /**
     * @param string $authAlgo
     *
     * @return $this
     */
    public function setAuthAlgo($authAlgo)
    {
        return $this->setParameter('auth_algo', $authAlgo);
    }

    /**
     * @param string $certUrl
     *
     * @return $this
     */
    public function setCertUrl($certUrl)
    {
        return $this->setParameter('cert_url', $certUrl);
    }

    /**
     * @param string $transmissionId
     *
     * @return $this
     */
    public function setTransmissionId($transmissionId)
    {
        return $this->setParameter('transmission_id', $transmissionId);
    }

    /**
     * @param string $transmissionSig
     *
     * @return $this
     */
    public function setTransmissionSig($transmissionSig)
    {
        return $this->setParameter('transmission_sig', $transmissionSig);
    }

    /**
     * @param string $transmissionTime
     *
     * @return $this
     */
    public function setTransmissionTime($transmissionTime)
    {
        return $this->setParameter('transmission_time', $transmissionTime);
    }

    /**
     * @param array $webhookEvent
     *
     * @return $this
     */
    public function setWebhookEvent(array $webhookEvent)
    {
        return $this->setParameter('webhook_event', $webhookEvent);
    }

    /**
     * @param string $webhookId
     *
     * @return $this
     */
    public function setWebhookId($webhookId)
    {
        return $this->setParameter('webhook_id', $webhookId);
    }

    /**
     * @param $data
     * @param $statusCode
     *
     * @return RestVerifyWebhookSignatureResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new RestVerifyWebhookSignatureResponse($this, $data, $statusCode);
    }
}
