<?php
declare(strict_types=1);

use App\Map;

require "vendor/autoload.php";

$m = new Map(101, 101);
$m->generate();

\App\Console::drawMatrix($m->matrix);
