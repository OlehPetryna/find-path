<?php
declare(strict_types=1);

namespace App;

use App\MapComponents\Cell;
use App\MapComponents\Coordinates;
use App\MapComponents\Matrix;

/**
 * Class MazeGenerator
 * @package App
 *
 * @property VisitedList $visited
 * @property int $cellsToProcessCount
 */
class MazeGenerator
{
    private $visited;
    private $cellsToProcessCount;

    /**
     * Generates labyrinth
     * @param Matrix $matrix
     * @param Cell $startPoint
     * @return Matrix
     * @throws \Exception
     */
    public function generate(Matrix $matrix, Cell $startPoint): Matrix
    {
        $movementStack = new \SplStack();
        $this->markAsVisited($startPoint);

        $currentPoint = $startPoint;

        while ($this->hasNotProcessedCells($matrix)) {
            /**@var Cell[] $neighbours */
            $neighbours = $matrix->getNeighboursFor($currentPoint, $this->visited, false);

            $neighbours = array_filter($neighbours, function ($v) {
                /**@var Cell|null $v */
                return $v !== null;
            });

            if (!empty($neighbours)) {
                $movementStack->push($currentPoint);
                $nextPoint = $neighbours[array_keys($neighbours)[random_int(0, count($neighbours) - 1)]];

                $removeWallsBetween = $this->removeWallsBetween($currentPoint, $nextPoint);
                $matrix->set($removeWallsBetween, Cell::createDestroyedWall($removeWallsBetween));

                $currentPoint = $nextPoint;
                $this->markAsVisited($currentPoint);
            } elseif (!$movementStack->isEmpty()) {
                $currentPoint = $movementStack->pop();
            } else {
                throw new \Exception("Unreachable statement");
            }
        }

        return $matrix;
    }


    private function removeWallsBetween(Cell $a, Cell $b): Coordinates
    {
        $newCoordinates = $a->getX() === $b->getX()
            ? Coordinates::fromArray([
                'row' => min($a->getY(), $b->getY()) + abs($a->getY() - $b->getY()) - 1,
                'col' => $a->getX()
            ])
            : Coordinates::fromArray([
                'row' => $a->getY(),
                'col' => min($a->getX(), $b->getX()) + abs($a->getX() - $b->getX()) - 1
            ]);

        return $newCoordinates;
    }

    private function markAsVisited(Cell $cell): void
    {
        if (!isset($this->visited))
            $this->visited = new VisitedList();

        $this->visited->add($cell);
    }

    private function hasNotProcessedCells(Matrix $matrix): bool
    {
        if (!isset($this->cellsToProcessCount))
            $this->cellsToProcessCount = $matrix->count(Cell::TYPE_PASSAGE);

        return $this->visited->count() < $this->cellsToProcessCount;
    }
}