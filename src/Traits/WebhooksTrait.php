<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    public function subscribeWebhook(string $callback, string $topic, int $lease = null, string $secret = null): Result
    {
        $attributes = [
            'hub.callback' => $callback,
            'hub.mode'     => 'subscribe',
            'hub.topic'    => urlencode($topic),
        ];

        if ($lease !== null) {
            $attributes['hub.lease_seconds'] = $lease;
        }

        if ($secret !== null) {
            $attributes['hub.secret'] = $secret;
        }

        return $this->post('webhooks/hub', $attributes);
    }

    public function unsubscribeWebhook(string $callback, string $topic): Result
    {
        $attributes = [
            'hub.callback' => $callback,
            'hub.mode'     => 'unsubscribe',
            'hub.topic'    => urlencode($topic),
        ];

        return $this->post('webhooks/hub', $attributes);
    }

    public function getWebhookSubscriptions(array $parameters = []): Result
    {
        return $this->get('webhooks/subscriptions', $parameters);
    }

    public function webhookTopicStreamMonitor(int $user): string
    {
        return static::BASE_URI . 'streams?user_id=' . $user;
    }

    public function webhookTopicUserFollows(int $from): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from;
    }

    public function webhookTopicUserGainsFollower(int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&to_id=' . $to;
    }

    public function webhookTopicUserFollowsUser(int $from, int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from . '&to_id' . $to;
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);
}
