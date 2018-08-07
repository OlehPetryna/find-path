<?php
declare(strict_types=1);

namespace App\MapComponents;


/**
 * Class Coordinates
 * @package App\MapComponents
 *
 * @property int $row, also known as Y coordinate
 * @property int $col, also known as X coordinate
 */
class Coordinates
{
    private $row;
    private $col;

    public function __construct(int $row, int $col)
    {
        $this->row = $row;
        $this->col = $col;
    }

    public static function fromArray(array $data): self
    {
        $instance = new self($data['row'], $data['col']);
        return $instance;
    }

    public function getRow()
    {
        return $this->row;
    }
    public function getCol()
    {
        return $this->col;
    }

    public function getX()
    {
        return $this->getCol();
    }

    public function getY()
    {
        return $this->getRow();
    }
}