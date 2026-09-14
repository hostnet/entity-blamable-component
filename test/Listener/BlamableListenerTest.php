<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Listener;

use Hostnet\Component\EntityBlamable\Attributes\Blamable;
use Hostnet\Component\EntityTracker\Event\EntityChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\EntityBlamable\Listener\BlamableListener
 */
class BlamableListenerTest extends TestCase
{
    private $em;
    private $resolver;
    private $provider;
    private $entity;

    public function setUp(): void
    {
        $this->em       = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->resolver = $this->createMock('Hostnet\Component\EntityBlamable\Resolver\BlamableResolverInterface');
        $this->provider = $this->createMock('Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface');
        $this->entity   = $this->createMock('Hostnet\Component\EntityBlamable\BlamableInterface');
    }

    public function testOnEntityChangedNoInterface(): void
    {
        $this->provider
            ->expects($this->never())
            ->method('getChangedAt');

        $event    = new EntityChangedEvent($this->em, new \stdClass(), new \stdClass(), []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }

    public function testOnEntityChangedExistingEntity(): void
    {
        $at = new \DateTime();
        $by = 'henk';

        $this->provider
            ->expects($this->once())
            ->method('getChangedAt')
            ->willReturn($at);

        $this->provider
            ->expects($this->once())
            ->method('getUpdatedBy')
            ->willReturn($by);

        $this->entity
            ->expects($this->once())
            ->method('setUpdatedAt')
            ->with($at);

        $this->entity
            ->expects($this->once())
            ->method('setUpdatedBy')
            ->with($by);

        $this->resolver
            ->expects($this->once())
            ->method('getBlamableAttribute')
            ->willReturn(new Blamable());

        $event    = new EntityChangedEvent($this->em, $this->entity, new \stdClass(), []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }

    public function testOnEntityChangedNewEntity(): void
    {
        $at = new \DateTime();
        $by = 'henk';

        $this->provider
            ->expects($this->once())
            ->method('getChangedAt')
            ->willReturn($at);

        $this->provider
            ->expects($this->once())
            ->method('getUpdatedBy')
            ->willReturn($by);

        $this->entity
            ->expects($this->once())
            ->method('setUpdatedAt')
            ->with($at);

        $this->entity
            ->expects($this->once())
            ->method('setCreatedAt')
            ->with($at);

        $this->entity
            ->expects($this->once())
            ->method('setUpdatedBy')
            ->with($by);

        $this->resolver
            ->expects($this->once())
            ->method('getBlamableAttribute')
            ->willReturn(new Blamable());

        $event    = new EntityChangedEvent($this->em, $this->entity, null, []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }

    public function testOnEntityChangedNewEntityWithAttribute(): void
    {
        $at = new \DateTime();
        $by = 'henk';

        $this->provider
            ->expects($this->once())
            ->method('getChangedAt')
            ->willReturn($at);

        $this->provider
            ->expects($this->once())
            ->method('getUpdatedBy')
            ->willReturn($by);

        $this->resolver
            ->expects($this->once())
            ->method('getBlamableAttribute')
            ->willReturn(new Blamable());

        $entity = new EntityWithAttribute();
        $event  = new EntityChangedEvent($this->em, $entity, null, []);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedBy());
        $this->assertNull($entity->getUpdatedAt());

        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);

        $this->assertSame($at, $entity->getCreatedAt());
        $this->assertSame($by, $entity->getUpdatedBy());
        $this->assertSame($at, $entity->getUpdatedAt());
    }

    public function testOnEntityChangedNewEntityWithInterfaceOnly(): void
    {
        $this->provider
            ->expects($this->never())
            ->method('getChangedAt');

        $this->provider
            ->expects($this->never())
            ->method('getUpdatedBy');

        $this->resolver
            ->expects($this->once())
            ->method('getBlamableAttribute')
            ->willReturn(null);

        $entity = new EntityWithInterfaceOnly();
        $event  = new EntityChangedEvent($this->em, $entity, null, []);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedBy());
        $this->assertNull($entity->getUpdatedAt());

        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedBy());
        $this->assertNull($entity->getUpdatedAt());

        $listener->entityChanged($event); // cover the case where the blamable cache is already filled.
    }
}
