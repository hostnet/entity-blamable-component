<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Resolver;

use Doctrine\ORM\EntityManagerInterface;

interface BlamableResolverInterface
{
    /**
     * Return the blamable annotation
     *
     * @param  EntityManagerInterface $em
     * @param  mixed                  $entity
     * @return Blamable
     */
    public function getBlamableAnnotation(EntityManagerInterface $em, $entity);
}
