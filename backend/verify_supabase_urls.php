<?php
// Verification script for full URL storage in Supabase
require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

echo "--- Supabase Full URL Implementation Test ---\n";

// 1. Test extractPathFromUrl
$baseUrl = config('filesystems.disks.supabase.url');
$testPath = "products/test/image.jpg";
$testUrl = $baseUrl . "/" . $testPath;

echo "Testing extractPathFromUrl...\n";
$extracted = ProductPhoto::extractPathFromUrl($testUrl);
if ($extracted === $testPath) {
    echo "✓ Path extracted correctly: $extracted\n";
} else {
    echo "✗ Path extraction FAILED. Expected: $testPath, Got: $extracted\n";
}

// 2. Test Model Creation with Full URL
echo "\nTesting ProductPhoto model creation with full URL...\n";
// Create a fake variant for testing if possible, or just use a dummy ID
$dummyId = 9999;
$photo = new ProductPhoto([
    'product_detail_id' => $dummyId,
    'photo_url' => $testUrl
]);

$processedUrl = $photo->photo_url;
if ($processedUrl === $testUrl) {
    echo "✓ Accessor handles full URL correctly.\n";
} else {
    echo "✗ Accessor modified URL unexpectedly. Got: $processedUrl\n";
}

// 3. Test Deletion logic
echo "\nTesting deletion logic preparation...\n";
try {
    $rawUrl = $testUrl;
    $pathForDeletion = ProductPhoto::extractPathFromUrl($rawUrl);
    echo "Path identified for deletion: $pathForDeletion\n";
    // We won't actually delete unless we uploaded, but the identification is key
} catch (Exception $e) {
    echo "Error identified: " . $e->getMessage() . "\n";
}

echo "\n--- Test Summary ---\n";
echo "The backend is now configured to store full URLs in the database.\n";
echo "Base URL: " . $baseUrl . "\n";
echo "-------------------------------------------\n";
