<?php
declare(strict_types=1);

namespace Tests;

use App\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testCreation(): void
    {
        $m = new Map(125, 130);

        $this->assertAttributeEquals([125, 130], "size", $m);

        $m0 = new Map();

        $this->assertAttributeEquals([100, 100], "size", $m0);
    }

    public function testGenerateCount(): void
    {
        $m = new Map();
        $m->generate();

        $this->assertAttributeCount(10000, "cells", $m);

        $m0 = new Map(5, 5);
        $m0->generate();

        $this->assertAttributeCount(25, "cells", $m0);
    }
}