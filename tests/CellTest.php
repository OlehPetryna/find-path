<?php
declare(strict_types=1);
namespace Tests;

use App\MapComponents\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testCreation(): void
    {
        $c = $this->getMockBuilder(Cell::class)
            ->setConstructorArgs([10, 20])
            ->getMock();

        $this->assertAttributeEquals([10, 20], "coords", $c);
    }
}