<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\OperationalHour;
use Carbon\Carbon;

Carbon::setLocale('id');
echo "Current Time: " . Carbon::now()->format('H:i:s') . "\n";
echo "Current Day: " . Carbon::now()->translatedFormat('l') . "\n\n";

echo "Check 'online': " . (OperationalHour::isOperational('online') ? 'OPEN' : 'CLOSED') . "\n";
echo "Check 'Website': " . (OperationalHour::isOperational('Website') ? 'OPEN' : 'CLOSED') . "\n";
echo "Check 'website': " . (OperationalHour::isOperational('website') ? 'OPEN' : 'CLOSED') . "\n";
