<?php
namespace Hostnet\Component\EntityBlamable\Provider;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 */
interface BlamableProviderInterface
{
    /**
     * @return string
     */
    public function getUpdatedBy();

    /**
     * @return \DateTime
     */
    public function getChangedAt();
}
