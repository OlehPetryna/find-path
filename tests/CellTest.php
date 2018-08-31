<?php
declare(strict_types=1);

namespace Tests;

use App\MapComponents\Cell;
use App\MapComponents\Coordinates;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testCreation(): void
    {
        $c = Cell::createPassage(new Coordinates(5, 5));
        $this->assertAttributeEquals([5, 5], 'coords', $c);
        $this->assertAttributeEquals(Cell::TYPE_PASSAGE, 'type', $c);
        $this->assertEquals(true, $c->isPassage());

        $c = Cell::createWall(new Coordinates(5, 8));
        $this->assertAttributeEquals([8, 5], 'coords', $c);
        $this->assertAttributeEquals(Cell::TYPE_WALL, 'type', $c);
        $this->assertEquals(true, $c->isWall());

        $c = Cell::createDestroyedWall(new Coordinates(1, 3));
        $this->assertAttributeEquals([3, 1], 'coords', $c);
        $this->assertAttributeEquals(Cell::TYPE_DESTROYED_WALL, 'type', $c);
        $this->assertEquals(false, $c->isWall());
    }

    public function testEquality(): void
    {
        $a = Cell::createDestroyedWall(new Coordinates(5, 2));
        $b = Cell::createDestroyedWall(new Coordinates(5, 2));

        $this->assertEquals(true, $a->equals($b));
        $this->assertEquals(true, $b->equals($a));

        $b = Cell::createWall(new Coordinates(5, 2));
        $this->assertEquals(false, $a->equals($b));
        $this->assertEquals(false, $b->equals($a));

        $b = Cell::createDestroyedWall(new Coordinates(3, 3));
        $this->assertEquals(false, $a->equals($b));
        $this->assertEquals(false, $b->equals($a));
    }

    public function testUniqueKey(): void
    {
        $c = Cell::createDestroyedWall(new Coordinates(6, 22));
        $this->assertEquals('6-22', $c->getUniqueKey());
    }
}