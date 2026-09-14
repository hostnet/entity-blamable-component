<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Provider;

interface BlamableProviderInterface
{
    public function getUpdatedBy(): string;

    public function getChangedAt(): \DateTimeInterface;
}
