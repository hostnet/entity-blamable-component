<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Provider;

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
