<?php

namespace App\Realtime\Auth;

use Illuminate\Support\Str;
use App\Models\Authorization;
use Thruway\Event\MessageEvent;
use Thruway\Event\NewRealmEvent;
use Thruway\Module\RouterModule;
use Thruway\Module\RealmModuleInterface;

class Subscription extends RouterModule implements RealmModuleInterface
{
    public function getSubscribedRealmEvents()
    {
        return [
            'SendSubscribedMessageEvent' => ['handleSubscribedMessage', 5]
        ];
    }

    public function handleSubscribedMessage(MessageEvent $event)
    {
        $subscription = $event->session->getRealm()->getBroker()->getSubscriptionById(
            $event->message->getSubscriptionId()
        );

        $channel = $subscription->getUri();
        if(!$this->isPrivate($channel)){
            return;
        }

        $authorization = Authorization::where('channel',$channel)
                                    ->where('session_id',$event->session->getSessionId())
                                    ->first();

        if (!$authorization) {
            $subscription->getSubscriptionGroup()->removeSubscription($subscription);
        }
    }

    public function handleSendWelcomeMessage(MessageEvent $event)
    {
        //
    }

    public static function getSubscribedEvents()
    {
        return [
            'new_realm' => ['handleNewRealm', 10],
        ];
    }

    public function handleNewRealm(NewRealmEvent $event)
    {
        $this->realms[$event->realm->getRealmName()] = $event->realm;

        $event->realm->addModule($this);
    }

    /**
     * Determine if channel is private or not
     *
     * @param string $channelName
     * @return boolean
     */
    private function isPrivate($channelName)
    {
        return Str::endsWith($channelName,'.private');
    }
}