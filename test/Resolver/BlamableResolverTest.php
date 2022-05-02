<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable\Resolver;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\EntityBlamable\Resolver\BlamableResolver
 */
class BlamableResolverTest extends TestCase
{
    private $provider;
    private $resolver;
    private $em;

    public function setUp(): void
    {
        $this->provider = $this
            ->getMockBuilder('Hostnet\Component\EntityTracker\Provider\EntityAnnotationMetadataProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $this->em = $this
            ->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resolver = new BlamableResolver($this->provider);
    }

    public function testGetBlamableAnnotation(): void
    {
        $entity = new \stdClass();

        $this->provider
            ->expects($this->once())
            ->method('getAnnotationFromEntity')
            ->with($this->em, $entity, 'Hostnet\Component\EntityBlamable\Blamable');

        $this->resolver->getBlamableAnnotation($this->em, $entity);
    }
}
