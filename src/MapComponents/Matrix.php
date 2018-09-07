<?php
declare(strict_types=1);

namespace App\MapComponents;


use App\VisitedList;

class Matrix implements \Iterator
{
    private $matrix = [];

    private $matrixByCols = [];

    private $position = 0;
    private $count = [];

    public function set(Coordinates $coordinates, Cell $c): void
    {
        if (!isset($this->matrix[$coordinates->getRow()]))
            $this->matrix[$coordinates->getRow()] = [];

        if (!isset($this->matrixByCols[$coordinates->getCol()]))
            $this->matrixByCols[$coordinates->getCol()] = [];

        if (!isset($this->matrix[$coordinates->getRow()][$coordinates->getCol()]))
            $this->count[$c->type] = isset($this->count[$c->type]) ? ++$this->count[$c->type] : 1;

        $this->matrixByCols[$coordinates->getCol()][$coordinates->getRow()] = $c;
        $this->matrix[$coordinates->getRow()][$coordinates->getCol()] = $c;
    }

    public function get(int $row, int $col): ?Cell
    {
        if (!isset($this->matrix[$row]) || !isset($this->matrix[$row][$col]))
            return null;

        return $this->matrix[$row][$col];
    }

    public function getByCoordinates(Coordinates $c): ?Cell
    {
        return $this->get($c->getRow(), $c->getCol());
    }

    public function count(int $type = -1): int
    {
        if ($type === -1)
            return array_sum($this->count);

        return $this->count[$type] ?? 0;
    }

    /**
     * @param Cell $c
     * @param VisitedList $exclude
     * @param bool $mode false if treating each second cell as a neighbour, true if each first
     * @return Cell[]|null[]
     */
    public function getNeighboursFor(Cell $c, VisitedList $exclude, bool $mode = false): array
    {
        return [
            $this->findClosestNeighbour($c, true, false, $exclude, $mode),
            $this->findClosestNeighbour($c, true, true, $exclude, $mode),
            $this->findClosestNeighbour($c, false, false, $exclude, $mode),
            $this->findClosestNeighbour($c, false, true, $exclude, $mode),
        ];
    }

    /**
     * @param Cell $c
     * @param bool $direction true to search in the same row, false to search in the same column
     * @param bool $reverse false to search from cells before provided cell, true to search starting from cells after provided cell
     * @param VisitedList $exclude
     * @param $mode
     * @return Cell|null
     */
    private function findClosestNeighbour(Cell $c, bool $direction, bool $reverse, VisitedList $exclude, $mode): ?Cell
    {
        $haystack = $direction ? $this->matrix[$c->getY()] : $this->matrixByCols[$c->getX()];

        $position = 0;
        foreach ($haystack as $idx => $cell) {
            /**@var Cell $cell */
            if ($cell->equals($c)) {
                $position = $idx;
                break;
            }
        }

        $haystack = $reverse
            ? array_slice($haystack, $position, count($haystack) - $position)
            : array_reverse(array_slice($haystack, 0, $position));

        $modeCondition = function (Cell $a, Cell $candidate) use ($mode, $exclude) {
            if ($mode) {
                return abs($a->getY() - $candidate->getY()) <= 1 && abs($a->getX() - $candidate->getX()) <= 1
                    && $candidate->isPassage()
                    && !$a->equals($candidate)
                    && !$exclude->contains($candidate);
            } else {
                return abs($a->getY() - $candidate->getY()) <= 2 && abs($a->getX() - $candidate->getX()) <= 2
                    && $a->type === $candidate->type
                    && !$a->equals($candidate)
                    && !$exclude->contains($candidate);
            }
        };

        foreach ($haystack as $cell) {
            /**@var Cell $cell */
            if ($modeCondition($c, $cell)) {
                return $cell;
            }
        }

        return null;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->matrix[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->matrix[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}