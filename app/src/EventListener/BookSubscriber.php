<?php

namespace App\EventListener;

use App\Entity\Author;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class BookSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getObjectManager();
        $uow = $em->getUnitOfWork();
        $scheduledUpdates = $uow->getScheduledEntityUpdates();

        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            $removedItems = $collection->getDeleteDiff();
            $addedItems = $collection->getInsertDiff();

            foreach ($removedItems as $item) {
                if ($item instanceof Author && !in_array($item, $scheduledUpdates)) {
                    $item->setBookAmount($item->getBookAmount() - 1);
                    $uow->recomputeSingleEntityChangeSet(
                        $em->getClassMetadata($item::class),
                        $item
                    );
                }
            }

            foreach ($addedItems as $item) {
                if ($item instanceof Author && !in_array($item, $scheduledUpdates)) {
                    $item->setBookAmount($item->getBookAmount() + 1);
                    $uow->recomputeSingleEntityChangeSet(
                        $em->getClassMetadata($item::class),
                        $item
                    );
                }
            }
        }

        if (count($uow->getScheduledEntityUpdates()) !== count($scheduledUpdates)) {
            // Recall flush() but with the author entities marked for update
            $em->flush();
        }
    }
}
