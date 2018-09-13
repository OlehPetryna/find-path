<?php
declare(strict_types=1);

namespace App;

use App\MapComponents\Cell;
use App\MapComponents\Coordinates;

class MazeRunner
{
    /**@var Map $maze */
    private $maze;

    /**@var Coordinates $position */
    private $position;

    public function enter(Map $map): void
    {
        $this->maze = $map;
    }

    public function findWayOut(): MazeRunnerTrace
    {
        $this->position = $this->maze->getEntryCoordinates();

        $movesStack = new \SplStack();
        $visited = new VisitedList();

        $movesStack->push($this->position);
        $visited->add($this->maze->getCellAt($this->position));

        $trace = new MazeRunnerTrace();
        $trace->add($this->position);

        while (!$this->isAtEnd($this->position)) {
            $neibs = array_filter($this->maze->getNeighbourCellsFor($this->maze->getCellAt($this->position), $visited),
                function ($v) {
                    return $v !== null;
                }
            );

            if (!empty($neibs)) {
                $nextPosition = $this->pickRandomPoint($neibs);
                $movesStack->push($nextPosition);
                $visited->add($this->maze->getCellAt($nextPosition));

                $this->position = $nextPosition;
                $trace->add($this->position);
            } elseif (!$movesStack->isEmpty()) {
                $this->position = $movesStack->pop();
            } else {
                throw new \Exception("Unreachable statement");
            }
        }
        $trace->add($this->position);

        return $trace;
    }

    /**
     * @param Cell[] $cells
     * @return Coordinates
     * @throws \Exception
     */
    public function pickRandomPoint(array $cells): Coordinates
    {
        return $cells[array_keys($cells)[random_int(0, count($cells) - 1)]]->getCoordinates();
    }

    private function isAtStart(Coordinates $c): bool
    {
        return $this->maze->isStartPoint($c);
    }

    private function isAtEnd(Coordinates $c): bool
    {
        return $this->maze->isEndPoint($c);
    }
}