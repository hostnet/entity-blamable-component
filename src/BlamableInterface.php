<?php
namespace Hostnet\Component\EntityBlamable;

/**
 * Implement on Entities to trigger the BlamableEntityListener
 *
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 */
interface BlamableInterface
{
    /**
     * @param string $by
     */
    public function setUpdatedBy($by);

    /**
     * @param \DateTime $at
     */
    public function setUpdatedAt(\DateTime $at);

    /**
     * @param \DateTime $at
     */
    public function setCreatedAt(\DateTime $at);
}
