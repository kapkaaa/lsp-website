<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\OperationalHour;
use Carbon\Carbon;

echo "Current Server Time: " . Carbon::now() . "\n";
echo "Current Server Timezone: " . date_default_timezone_get() . "\n";
echo "Current App Timezone: " . config('app.timezone') . "\n";
echo "Carbon Locale: " . Carbon::getLocale() . "\n";
Carbon::setLocale('id');
echo "Translated Day: " . Carbon::now()->translatedFormat('l') . "\n";

echo "\n--- Operational Hours from DB ---\n";
$hours = OperationalHour::all();
echo json_encode($hours, JSON_PRETTY_PRINT);
