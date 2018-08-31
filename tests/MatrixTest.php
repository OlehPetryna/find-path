<?php
declare(strict_types=1);

namespace Tests;

use App\MapComponents\Cell;
use App\MapComponents\Coordinates;
use App\MapComponents\Matrix;
use App\VisitedList;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    public function testMain(): void
    {
        $m = new Matrix();

        for ($i = 0; $i <= 10; $i++) {
            for ($j = 0; $j <= 10; $j++) {
                $m->set(new Coordinates($i, $j), new Cell($i, $j, Cell::TYPE_PASSAGE));
            }
        }

        $neibs = $m->getNeighboursFor($m->get(5, 5), new VisitedList());

        $this->assertTrue(count($neibs) === 4);
        $this->assertContainsOnlyInstancesOf(Cell::class, $neibs);

        $this->assertContains($m->get(6, 5), $neibs);
        $this->assertContains($m->get(4, 5), $neibs);
        $this->assertContains($m->get(5, 4), $neibs);
        $this->assertContains($m->get(5, 6), $neibs);

        $m->set(new Coordinates(6, 8), Cell::createWall(new Coordinates(6, 8)));
        $m->set(new Coordinates(8, 6), Cell::createWall(new Coordinates(8, 6)));
        $m->set(new Coordinates(10, 8), Cell::createWall(new Coordinates(10, 8)));
        $m->set(new Coordinates(8, 10), Cell::createWall(new Coordinates(8, 10)));

        $v = new VisitedList();

        $v->add($m->get(7, 8));
        $v->add($m->get(8, 7));
        $v->add($m->get(9, 8));
        $v->add($m->get(8, 9));

        $neibs = $m->getNeighboursFor($m->get(8, 8), $v);
        $this->assertContainsOnly('null', $neibs, true);
    }
}