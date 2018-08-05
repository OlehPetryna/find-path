<?php
declare(strict_types=1);

namespace App;

use App\Console;
use App\MapComponents\Cell;
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
        Console::drawMatrix($this->matrix);

        $startPoint = $this->matrix->get(1, 1);
        $visited[$startPoint->getUniqueKey()] = $startPoint;

        $currentPoint = $startPoint;
        while (count($visited) < $this->matrix->count()) {

            echo "visited: " . count($visited) . " " . $this->matrix->count() . "\n";

            /**@var Cell[] $neighbours*/
            $neighbours = $this->matrix->getNeighboursFor($currentPoint, $visited);
            $neighbours = array_filter($neighbours, function ($v) use ($visited) {
                /**@var Cell|null $v */
                return $v !== null;
            });

            if (!empty($neighbours)) {
                echo "1\n";
                $movementStack->push($currentPoint);
                $randIdx = array_keys($neighbours)[random_int(0, count($neighbours) - 1)];
                $nextPoint = $neighbours[$randIdx];
                $this->removeWallsBetween($currentPoint, $nextPoint);
                $currentPoint = $nextPoint;
                $visited[$currentPoint->getUniqueKey()] = $currentPoint;
            } elseif (!$movementStack->isEmpty()) {
                echo "2\n";
                $currentPoint = $movementStack->pop();

                echo "backtrack: {$currentPoint->getY()} {$currentPoint->getX()}\n";
            } else {

                Console::drawMatrix($this->matrix);
                throw new \Exception("Unreachable statement");
            }
        }

    }

    private function pickRandomPassage(array $exclude) {
        $res = null;
        while($res === null) {
            $randX = random_int(0, $this->getWidth());
            $randY = random_int(0, $this->getHeight());

            $res = isset($exclude["{$randX}-{$randY}"]) ? null : $this->matrix->get($randY, $randX);
        }

        return $res;
    }

    private function removeWallsBetween(Cell $a, Cell $b): void
    {
        $new = $a->getX() === $b->getX()
            ? $this->matrix->get(min($a->getY(), $b->getY()) + abs($a->getY() - $b->getY()) - 1, $a->getX())
            : $this->matrix->get($a->getY(), min($a->getX(), $b->getX()) + abs($a->getX() - $b->getX()) - 1);

//        echo "removing wall between {$a->getY()} {$a->getX()} AND {$b->getY()} {$b->getX()} === {$new->getY()} {$new->getX()}\n";
        $new->type = Cell::TYPE_DESTROYED_WALL;
        $this->matrix->set($new->getY(), $new->getX(), $new);
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
                $matrix->set($row, $col,
                    $col % 2 !== 0 && $row % 2 !== 0 && $row < $this->getHeight() - 1 && $col < $this->getWidth() - 1
                        ? Cell::createPassage($col, $row)
                        : Cell::createWall($col, $row));
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