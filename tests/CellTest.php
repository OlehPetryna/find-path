<?php
declare(strict_types=1);
namespace Tests;

use App\MapComponents\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testCreation(): void
    {
        $c = new Cell(10, 20);
        $this->assertAttributeEquals([10, 20], "coords", $c);
    }
}