<?php
declare(strict_types=1);

namespace App\MapComponents;


class Matrix implements \Iterator
{
    private $matrix = [];

    private $position = 0;
    private $count;

    public function set(int $row, int $col, Cell $c): void
    {

        if (!isset($this->matrix[$row]))
            $this->matrix[$row] = [];


        if (!isset($this->matrix[$row][$col]))
            ++$this->count;

        $this->matrix[$row][$col] = $c;
    }

    public function get(int $row, int $col): ?Cell
    {
        if (!isset($this->matrix[$row]) || !isset($this->matrix[$row][$col]))
            return null;

        return $this->matrix[$row][$col];
    }

    public function count(): int
    {
        return $this->count;
    }

    /**
     * @param Cell $c
     * @param Cell[] $exclude
     * @return Cell|null[]
     */
    public function getNeighboursFor(Cell $c, array $exclude): array
    {
        return [
            $this->findClosestNeighbour($c, true, false, $exclude),
            $this->findClosestNeighbour($c, true, true, $exclude),
            $this->findClosestNeighbour($c, false, false, $exclude),
            $this->findClosestNeighbour($c, false, true, $exclude),
        ];
    }

    /**
     * @param Cell $c
     * @param bool $direction true to search in the same row, false to search in the same column
     * @param bool $reverse false to search from cells before provided cell, true to search starting from cells after provided cell
     * @param Cell[] $exclude
     * @return Cell|null
     */
    private function findClosestNeighbour(Cell $c, bool $direction, bool $reverse, array $exclude): ?Cell
    {
        $haystack = $direction ? $this->matrix[$c->getY()] : array_map(function ($row) use ($c) {
            return $row[$c->getX()];
        }, $this->matrix);

        $position = 0;
        foreach ($haystack as $idx => $cell) {
            /**@var Cell $cell */
            if ($cell->equals($c))
                $position = $idx;
        }

        $haystack = $reverse
            ? array_slice($haystack, $position, count($haystack) - $position)
            : array_reverse(array_slice($haystack, 0, $position));

        foreach ($haystack as $cell) {
            /**@var Cell $cell */
            if (abs($cell->getY() - $c->getY()) <= 2 && abs($cell->getX() - $c->getX()) <= 2
                && $cell->type === $c->type
                && !$c->equals($cell)
                && !isset($exclude[$cell->getUniqueKey()])) {

                echo "found neib for {$c->getY()} {$c->getX()} $c->type == {$cell->getY()} {$cell->getX()} $cell->type\n";
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