<?php
declare(strict_types=1);

namespace Tests;

use App\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testCreation(): void
    {
        $m = $this->getMockBuilder(Map::class)
            ->setConstructorArgs([125, 130])
            ->getMock();

        $this->assertAttributeEquals([125, 130], "size", $m);

        $m0 = $this->getMockBuilder(Map::class)
            ->getMock();

        $this->assertAttributeEquals([100, 100], "size", $m0);
    }
}