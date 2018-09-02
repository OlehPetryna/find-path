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
 * @property Cell $startPoint
 * @property Cell $endPoint
 */
class Map
{
    private $size;
    public $matrix = [];

    private $startPoint;
    private $endPoint;

    public function __construct(int $width = 100, int $height = 100)
    {
        $width = $width % 2 === 0 ? ++$width : $width;
        $height = $height % 2 === 0 ? ++$height : $height;
        $this->size = [$width, $height];
    }

    /**
     * Generates labyrinth
     * @throws \Exception
     */
    public function generate()
    {
        $this->matrix = $this->createMatrix();

        $this->startPoint =$this->pickStartPoint($this->matrix);
        $this->endPoint = $this->pickEndPoint($this->matrix);

        $generator = new MazeGenerator();
        $this->matrix = $generator->generate($this->matrix, $this->startPoint);
    }

    public function getEntryCoordinates(): Coordinates
    {
        return $this->startPoint->getCoordinates();
    }

    public function isStartPoint(Coordinates $c): bool
    {
        return $this->startPoint->equals($this->matrix->get($c->getRow(), $c->getCol()));
    }

    public function isEndPoint(Coordinates $c): bool
    {
        return $this->endPoint->equals($this->matrix->get($c->getRow(), $c->getCol()));
    }

    /**
     * @param Cell $cell
     * @param VisitedList $visitedList
     * @return Cell[]|null[]
     */
    public function getNeighbourCellsFor(Cell $cell, VisitedList $visitedList): array
    {
        return $this->matrix->getNeighboursFor($cell, $visitedList, true);
    }

    public function getCellAt(Coordinates $coordinates): ?Cell
    {
        return $this->matrix->getByCoordinates($coordinates);
    }

    private function pickStartPoint(Matrix $m): Cell
    {
        //picking always from higher-left quarter of matrix
        $point = null;

        $widthBoundary = (int)floor($this->getWidth() / 2);
        $heightBoundary = (int)floor($this->getHeight() / 2);
        while ($point === null) {
            $row = random_int(1, $heightBoundary);
            $col = random_int(1, $widthBoundary);

            $cell = $m->get($row, $col);
            if ($cell && $cell->isPassage())
                $point = $cell;
        }

        return $point;
    }

    private function pickEndPoint(Matrix $m): Cell
    {
        //picking always from lower-right quarter of matrix
        $point = null;

        $widthBoundary = (int)floor($this->getWidth() / 2);
        $heightBoundary =(int)floor($this->getHeight() / 2);
        while ($point === null) {
            $row = random_int($heightBoundary, $this->getHeight());
            $col = random_int($widthBoundary, $this->getWidth());

            $cell = $m->get($row, $col);
            if ($cell && $cell->isPassage())
                $point = $cell;
        }

        return $point;
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