<?php
namespace Hostnet\Component\EntityBlamable\Resolver;

/**
 * @coversDefaultClass Hostnet\Component\EntityBlamable\Resolver\BlamableResolver
 * @covers ::__construct
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class BlamableResolverTest extends \PHPUnit_Framework_TestCase
{
    private $provider;
    private $resolver;
    private $em;

    public function setUp()
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

    /**
     * @covers ::getBlamableAnnotation
     */
    public function testGetBlamableAnnotation()
    {
        $entity = new \stdClass();

        $this->provider
            ->expects($this->once())
            ->method('getAnnotationFromEntity')
            ->with($this->em, $entity, 'Hostnet\Component\EntityBlamable\Blamable');

        $this->resolver->getBlamableAnnotation($this->em, $entity);
    }
}
