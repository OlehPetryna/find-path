<?php
declare(strict_types=1);

use App\Map;

require "vendor/autoload.php";

$m = new Map(100, 32);
$m->generate();

\App\Console::drawMatrix($m->matrix);

$runner = new \App\MazeRunner();
$runner->enter($m);
$res = $runner->findWayOut();

echo "\n";
echo "\n";

\App\Console::drawRunnerTrace($m, $res);
