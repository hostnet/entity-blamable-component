<?php
namespace Hostnet\Component\EntityBlamable\Listener;

use Hostnet\Component\EntityBlamable\BlamableInterface;
use Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface;
use Hostnet\Component\EntityBlamable\Resolver\BlamableResolverInterface;
use Hostnet\Component\EntityTracker\Event\EntityChangedEvent;

/**
 * Listens to "Events::entityChanged"
 *
 * Attempts to set updated at, created at, updated by and updated at fields
 * in an entity using @Blamable and implementing the BlamableInterface
 *
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 */
class BlamableListener
{
    /**
     * @var BlamableResolverInterface
     */
    private $resolver;

    /**
     * @var BlamableProviderInterface
     */
    private $provider;

    /**
     * @param BlamableResolverInterface $resolver
     * @param BlamableProviderInterface $provider
     */
    public function __construct(
        BlamableResolverInterface $resolver,
        BlamableProviderInterface $provider
    ) {
        $this->resolver = $resolver;
        $this->provider = $provider;
    }

    /**
     * @param EntityChangedEvent $event
     */
    public function entityChanged(EntityChangedEvent $event)
    {
        $entity     = $event->getCurrentEntity();
        $annotation = $this->resolver->getBlamableAnnotation($event->getEntityManager(), $entity);

        if (null === $annotation || !$entity instanceof BlamableInterface) {
            return;
        }

        $changed_at = $this->provider->getChangedAt();
        $updated_by = $this->provider->getUpdatedBy();

        $entity->setUpdatedBy($updated_by);
        $entity->setUpdatedAt($changed_at);

        if ($event->getEntityManager()->getUnitOfWork()->isScheduledForInsert($entity)) {
            // new entity, also fill in created at
            $entity->setCreatedAt($changed_at);
        }
    }
}
