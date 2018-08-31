<?php
declare(strict_types=1);

namespace App\MapComponents;


class Cell
{
    private $coords = [];
    public $type;

    const TYPE_WALL = 1;
    const TYPE_PASSAGE = 2;
    const TYPE_DESTROYED_WALL = 3;

    public function __construct(int $x, int $y, int $type)
    {
        $this->coords = [$x, $y];
        $this->type = $type;
    }

    public static function createWall(Coordinates $coordinates): self
    {
        return new self($coordinates->getX(), $coordinates->getY(), self::TYPE_WALL);
    }

    public static function createPassage(Coordinates $coordinates): self
    {
        return new self($coordinates->getX(), $coordinates->getY(), self::TYPE_PASSAGE);
    }

    public static function createDestroyedWall(Coordinates $coordinates): self
    {
        return new self($coordinates->getX(), $coordinates->getY(), self::TYPE_DESTROYED_WALL);
    }

    public function isWall()
    {
        return $this->type === self::TYPE_WALL;
    }

    public function isPassage()
    {
        return $this->type === self::TYPE_PASSAGE;
    }

    public function getX()
    {
        return $this->coords[0];
    }

    public function getY()
    {
        return $this->coords[1];
    }

    public function getUniqueKey()
    {
        return self::generateUniqueKey($this->getY(), $this->getX());
    }

    public static function generateUniqueKey(int $row, int $col): string
    {
        return $row . "-" . $col;
    }

    public function equals(Cell $c)
    {
        return $this->getY() === $c->getY() && $this->getX() === $c->getX() && $this->type === $c->type;
    }
}