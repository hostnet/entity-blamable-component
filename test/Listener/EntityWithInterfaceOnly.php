<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Listener;

use Hostnet\Component\EntityBlamable\BlamableInterface;

class EntityWithInterfaceOnly implements BlamableInterface
{
    private ?\DateTimeInterface $created_at = null;

    private ?string $updated_by             = null;
    private ?\DateTimeInterface $updated_at = null;

    public function setUpdatedBy(string $by): static
    {
        $this->updated_by = $by;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    public function setUpdatedAt(\DateTimeInterface $at): static
    {
        $this->updated_at = $at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setCreatedAt(\DateTimeInterface $at): static
    {
        $this->created_at = $at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
}
