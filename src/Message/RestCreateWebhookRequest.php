<?php

namespace Omnipay\PayPal\Message;

final class RestCreateWebhookRequest extends AbstractRestRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [
            'event_types' => \array_map(
                function ($value) {
                    return ['name' => $value];
                },
                $this->getEventTypes()
            ),
            'url' => $this->getUrl(),
        ];
    }

    /**
     * @return array
     */
    public function getEventTypes()
    {
        return $this->getParameter('event_types') ?: [];
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint()
    {
        return parent::getEndpoint().'/notifications/webhooks';
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->getParameter('webhook_url');
    }

    /**
     * @param array $eventTypes
     *
     * @return $this
     */
    public function setEventTypes(array $eventTypes)
    {
        return $this->setParameter('event_types', $eventTypes);
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        return $this->setParameter('webhook_url', $url);
    }
}
