<?php
declare(strict_types=1);

namespace App;

use App\MapComponents\Cell;

/**@property Cell[] $cells */
class Map
{
    private $size;
    private $cells;

    public function __construct(int $width = 100, int $height = 100)
    {
        $this->size = [$width, $height];
    }

    public function generate()
    {
        $this->fillEmptyCells();
    }


    private function fillEmptyCells()
    {
        for ($x = 0; $x < $this->getWidth(); $x++) {
            for ($y = 0; $y < $this->getHeight(); $y++) {
                $this->cells["$x-$y"] = new Cell($x, $y);
            }
        }
    }

    private function getWidth(): int
    {
        return $this->size[0];
    }

    private function getHeight(): int
    {
        return $this->size[1];
    }
}