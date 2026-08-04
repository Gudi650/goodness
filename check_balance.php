<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$c = app(App\Http\Controllers\balanceSheetController::class);
$m = new ReflectionMethod($c, 'reportData');
$m->setAccessible(true);
$d = $m->invoke($c);
var_export($d['equityLiabilities']);
