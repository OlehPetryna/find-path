<?php
declare(strict_types=1);

use App\Map;

require "vendor/autoload.php";

$m = new Map(13, 13);
$m->generate();

\App\Console::drawMatrix($m->matrix);