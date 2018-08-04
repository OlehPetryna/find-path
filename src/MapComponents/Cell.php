<?php
declare(strict_types=1);

namespace App\MapComponents;


class Cell
{
    private $coords = [];
    public function __construct(int $x, int $y)
    {
        $this->coords = [$x, $y];
    }
}