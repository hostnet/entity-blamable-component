<?php
namespace Hostnet\Component\EntityBlamable\Resolver;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
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
