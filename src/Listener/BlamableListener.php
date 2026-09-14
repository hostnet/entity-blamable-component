<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Hostnet\Component\EntityBlamable\BlamableInterface;
use Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface;
use Hostnet\Component\EntityBlamable\Resolver\BlamableResolverInterface;
use Hostnet\Component\EntityTracker\Event\EntityChangedEvent;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * Listens to "Events::entityChanged"
 *
 * Attempts to set updated at, created at, updated by and updated at fields
 * in an entity using #[Blamable] and implementing the BlamableInterface
 */
class BlamableListener
{
    public function __construct(
        private BlamableResolverInterface $resolver,
        private BlamableProviderInterface $provider,
        private CacheItemPoolInterface $is_blamable_cache = new ArrayAdapter()
    ) {
    }

    public function entityChanged(EntityChangedEvent $event): void
    {
        $entity = $event->getCurrentEntity();

        if (!$this->isBlamable($event->getEntityManager(), $entity)) {
            return;
        }

        $changed_at = $this->provider->getChangedAt();
        $updated_by = $this->provider->getUpdatedBy();

        $entity->setUpdatedBy($updated_by);
        $entity->setUpdatedAt($changed_at);

        if (null === $event->getOriginalEntity()) {
            // new entity, also fill in created at
            $entity->setCreatedAt($changed_at);
        }
    }

    private function isBlamable(EntityManagerInterface $em, object $entity): bool
    {
        $cache_key   = base64_encode('BLAMABLE-' . $entity::class);
        $cached_item = $this->is_blamable_cache->getItem($cache_key);

        if ($cached_item->isHit()) {
            return $cached_item->get();
        }

        if (!($entity instanceof BlamableInterface)) {
            return $this->save($cached_item, false);
        }

        if (null !== $this->resolver->getBlamableAttribute($em, $entity)) {
            return $this->save($cached_item, true);
        }

        return $this->save($cached_item, false);
    }

    private function save(CacheItemInterface $item, bool $value): bool
    {
        $item->set($value);
        $this->is_blamable_cache->save($item);

        return $value;
    }
}
