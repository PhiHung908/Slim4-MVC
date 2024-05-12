<?php
declare(strict_types=1);

$classLoader = require __DIR__ . '/../../vendor/autoload.php';
$psr4PathHelper = new hSlim\base\Psr4PathHelper($classLoader);

(require_once $psr4PathHelper->findFile('hSlim/base/actions/bootstrapIndex.php', true));
