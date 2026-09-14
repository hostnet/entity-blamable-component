<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable;

/**
 * Implement on Entities to trigger the BlamableEntityListener
 */
interface BlamableInterface
{
    public function setUpdatedBy(string $by): static;

    public function setUpdatedAt(\DateTimeInterface $at): static;

    public function setCreatedAt(\DateTimeInterface $at): static;
}
