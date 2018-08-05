<?php
declare(strict_types=1);

namespace App;


use App\MapComponents\Matrix;

class Console
{
    public static function drawMatrix(Matrix $m)
    {
        foreach ($m as $rowIdx => $row) {
            foreach ($row as $colIdx => $col) {
                $c = $m->get($rowIdx, $colIdx);
                echo $c->isWall() ? "|" : "*";
            }
            echo "\n";
        }
    }
}