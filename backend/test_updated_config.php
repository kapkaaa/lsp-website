<?php
// Test script to check the updated Supabase configuration

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

echo "Testing updated Supabase configuration...\n";

// Create a temporary file to simulate an upload
$tempFilePath = tempnam(sys_get_temp_dir(), 'test_upload');
file_put_contents($tempFilePath, 'test image content for updated config');

// Create an UploadedFile instance
$uploadedFile = new UploadedFile(
    $tempFilePath,
    'test_image_updated.jpg',
    'image/jpeg',
    null,
    true // test mode
);

try {
    // Store on supabase disk with updated config
    $pathOnSupabase = $uploadedFile->store('products', 'supabase');
    echo "Path returned when storing to supabase with updated config: " . $pathOnSupabase . "\n";
    
    // Check if the file exists on supabase
    $supabaseDisk = Storage::disk('supabase');
    if ($supabaseDisk->exists($pathOnSupabase)) {
        echo "✓ File exists on supabase disk at: " . $pathOnSupabase . "\n";
    } else {
        echo "✗ File does NOT exist on supabase disk at: " . $pathOnSupabase . "\n";
    }
    
    // Try to get the URL for the file
    try {
        $url = $supabaseDisk->url($pathOnSupabase);
        echo "Generated URL for the file: " . $url . "\n";
    } catch (Exception $e) {
        echo "Error generating URL: " . $e->getMessage() . "\n";
    }
    
    // Clean up temp file
    unlink($tempFilePath);
    
    echo "\nTest completed. Check your Laravel log file for any error messages.\n";
    
} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
    var_dump($e);
}