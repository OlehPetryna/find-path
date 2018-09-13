<?php
declare(strict_types=1);

namespace App;


use App\MapComponents\Coordinates;

class MazeRunnerTrace
{
    private $trace = [];

    public function add(Coordinates $c): void
    {
        $this->trace[$c->getRow() . '-' . $c->getCol()] = $c;
    }

    public function contains(Coordinates $c) : bool
    {
        return isset($this->trace[$c->getRow() . '-' . $c->getCol()]);
    }
}