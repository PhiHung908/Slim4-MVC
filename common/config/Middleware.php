<?php

declare(strict_types=1);

use hSlim\base\actions\middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
};
