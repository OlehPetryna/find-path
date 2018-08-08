<?php
declare(strict_types=1);

namespace App;

use App\Console;
use App\MapComponents\Cell;
use App\MapComponents\Coordinates;
use App\MapComponents\Matrix;

/**
 * Class Map
 * @package App
 *
 * @property int[] $size
 * @property Matrix $matrix
 */
class Map
{
    private $size;
    public $matrix = [];

    public function __construct(int $width = 100, int $height = 100)
    {
        $this->size = [$width, $height];
    }

    /**
     * Generates labyrinth
     */
    public function generate()
    {
        $movementStack = new \SplStack();
        $visited = [];

        $this->matrix = $this->createMatrix();
        $startPoint = $this->matrix->get(1, 1);
        $visited[$startPoint->getUniqueKey()] = $startPoint;

        $currentPoint = $startPoint;
        while (count($visited) < $this->matrix->count(Cell::TYPE_PASSAGE)) {
            /**@var Cell[] $neighbours*/
            $neighbours = $this->matrix->getNeighboursFor($currentPoint, $visited);
            $neighbours = array_filter($neighbours, function ($v) use ($visited) {
                /**@var Cell|null $v */
                return $v !== null;
            });

            if (!empty($neighbours)) {
                $movementStack->push($currentPoint);
                $randIdx = array_keys($neighbours)[random_int(0, count($neighbours) - 1)];
                $nextPoint = $neighbours[$randIdx];
                $this->removeWallsBetween($currentPoint, $nextPoint);
                $currentPoint = $nextPoint;
                $visited[$currentPoint->getUniqueKey()] = $currentPoint;
            } elseif (!$movementStack->isEmpty()) {
                $currentPoint = $movementStack->pop();
            } else {
                throw new \Exception("Unreachable statement");
            }
        }
    }

    private function removeWallsBetween(Cell $a, Cell $b): void
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

        $this->matrix->set($newCoordinates, Cell::createDestroyedWall($newCoordinates));
    }

    /**
     * Builds matrix with walls & passages
     *
     * @return Matrix
     */
    private function createMatrix(): Matrix
    {
        $matrix = new Matrix();
        for ($row = 0; $row < $this->getHeight(); $row++) {
            for ($col = 0; $col < $this->getWidth(); $col++) {
                $pair = Coordinates::fromArray([
                    'row' => $row,
                    'col' => $col
                ]);
                $matrix->set($pair,
                    $col % 2 !== 0 && $row % 2 !== 0 && $row < $this->getHeight() - 1 && $col < $this->getWidth() - 1
                        ? Cell::createPassage($pair)
                        : Cell::createWall($pair));
            }
        }
        return $matrix;
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