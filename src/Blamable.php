<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\EntityBlamable;

use Hostnet\Component\EntityTracker\Annotation\Tracked;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Blamable extends Tracked
{
}
