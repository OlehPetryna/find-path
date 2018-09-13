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

    public static function drawRunnerTrace(Map $map, MazeRunnerTrace $trace)
    {
        foreach ($map->matrix as $rowIdx => $row) {
            foreach ($row as $colIdx => $col) {
                $c = $map->matrix->get($rowIdx, $colIdx);

                $char = $c->isWall() ? "|" : "*";
                if ($trace->contains($c->getCoordinates()))
                    $char = "1";

                if ($map->isStartPoint($c->getCoordinates()))
                    $char = "0";

                if ($map->isEndPoint($c->getCoordinates()))
                    $char = "2";

                echo $char;
            }
            echo "\n";
        }
    }
}