<?php
namespace Hostnet\Component\EntityBlamable;

use Hostnet\Component\EntityTracker\Annotation\Tracked;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class Blamable extends Tracked
{
}
