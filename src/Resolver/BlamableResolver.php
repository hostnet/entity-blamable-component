<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Resolver;

use Doctrine\ORM\EntityManagerInterface;
use Hostnet\Component\EntityBlamable\Attributes\Blamable;
use Hostnet\Component\EntityTracker\Provider\EntityMetadataProvider;

class BlamableResolver implements BlamableResolverInterface
{
    public function __construct(private EntityMetadataProvider $provider)
    {
    }

    public function getBlamableAttribute(EntityManagerInterface $em, object $entity): ?Blamable
    {
        return $this->provider->getAttributeFromEntity(Blamable::class, $em, $entity);
    }
}
