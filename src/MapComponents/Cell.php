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

    public function __construct(int $x, int $y)
    {
        $this->coords = [$x, $y];
    }

    public static function createWall(int $x, int $y): self
    {
        $c = new self($x, $y);
        $c->type = self::TYPE_WALL;

        return $c;
    }

    public static function createPassage(int $x, int $y): self
    {
        $c = new self($x, $y);
        $c->type = self::TYPE_PASSAGE;

        return $c;
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
        return $this->getY() . "-" . $this->getX();
    }

    public function equals(Cell $c)
    {
        return $this->getY() === $c->getY() && $this->getX() === $c->getX();
    }
}