<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Resolver;

use Doctrine\ORM\EntityManagerInterface;
use Hostnet\Component\EntityBlamable\Attributes\Blamable;

interface BlamableResolverInterface
{
    public function getBlamableAttribute(EntityManagerInterface $em, object $entity): ?Blamable;
}
