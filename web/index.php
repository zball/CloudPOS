<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

// Temporary function - should probably unit test this
function env($key, $default = 'prod')
{
    if (false !== $value = getenv($key)) {
        return $value;
    }

    return $default;
}

$kernel = new AppKernel(
    env('APP_ENV'),
    env('APP_ENV' === 'prod' ? false : true)
);

$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
