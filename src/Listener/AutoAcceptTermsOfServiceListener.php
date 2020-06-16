<?php

declare(strict_types=1);

namespace Michels\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Jobs\Entity\JobInterface;
use Jobs\Entity\JobSnapshot;

class AutoAcceptTermsOfServiceListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $doc = $args->getDocument();

        if (!$doc instanceof JobInterface || $doc instanceof JobSnapshot) {
            return;
        }

        $doc->setTermsAccepted(true);
    }
}
