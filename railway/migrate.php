<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$connection = DB::connection();
$driver = $connection->getDriverName();
$lockAcquired = false;

if ($driver === 'mysql') {
    $result = $connection->selectOne(
        'SELECT GET_LOCK(?, 120) AS acquired',
        ['biswas-performance-migrations']
    );
    $lockAcquired = (int) ($result->acquired ?? 0) === 1;
} elseif ($driver === 'pgsql') {
    $connection->selectOne('SELECT pg_advisory_lock(?)', [4872912310]);
    $lockAcquired = true;
}

if (in_array($driver, ['mysql', 'pgsql'], true) && !$lockAcquired) {
    fwrite(STDERR, "Could not acquire the database migration lock within 120 seconds.\n");
    exit(1);
}

$exitCode = 1;

try {
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    fwrite(STDOUT, Artisan::output());
} finally {
    if ($driver === 'mysql' && $lockAcquired) {
        $connection->selectOne('SELECT RELEASE_LOCK(?)', ['biswas-performance-migrations']);
    } elseif ($driver === 'pgsql' && $lockAcquired) {
        $connection->selectOne('SELECT pg_advisory_unlock(?)', [4872912310]);
    }
}

exit($exitCode);
