<?php
declare(strict_types=1);

namespace App;

use App\MapComponents\Cell;

class VisitedList
{
    private $storage;

    public function add(Cell $c): void
    {
        $this->storage[$c->getUniqueKey()] = $c;
    }

    public function count(): int
    {
        return count($this->storage);
    }

    public function contains(Cell $c): bool
    {
        return isset($this->storage[$c->getUniqueKey()]);
    }
}