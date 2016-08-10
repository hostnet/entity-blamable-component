<?php
namespace Hostnet\Component\EntityBlamable\Listener;

use Hostnet\Component\EntityBlamable\Blamable;
use Hostnet\Component\EntityTracker\Event\EntityChangedEvent;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @covers Hostnet\Component\EntityBlamable\Listener\BlamableListener
 */
class BlamableListenerTest extends \PHPUnit_Framework_TestCase
{
    private $em;
    private $resolver;
    private $provider;
    private $entity;
    private $uow;

    public function setUp()
    {
        $this->em       = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->resolver = $this->createMock('Hostnet\Component\EntityBlamable\Resolver\BlamableResolverInterface');
        $this->provider = $this->createMock('Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface');
        $this->entity   = $this->createMock('Hostnet\Component\EntityBlamable\BlamableInterface');
    }

    public function testOnEntityChangedNoInterface()
    {
        $this->provider
            ->expects($this->never())
            ->method('getChangedAt');

        $event    = new EntityChangedEvent($this->em, new \stdClass(), new \stdClass(), []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }

    public function testOnEntityChangedExistingEntity()
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
            ->method('getBlamableAnnotation')
            ->willReturn(new Blamable());

        $event    = new EntityChangedEvent($this->em, $this->entity, new \stdClass(), []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }

    public function testOnEntityChangedNewEntity()
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
            ->method('getBlamableAnnotation')
            ->willReturn(new Blamable());

        $event    = new EntityChangedEvent($this->em, $this->entity, null, []);
        $listener = new BlamableListener($this->resolver, $this->provider);
        $listener->entityChanged($event);
    }
}
