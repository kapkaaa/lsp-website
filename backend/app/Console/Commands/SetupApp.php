<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup instructions for the DistroZone Admin Panel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('DistroZone Admin Panel Setup Instructions:');
        $this->line('');
        $this->info('1. Make sure you have a MySQL server running');
        $this->info('2. Create a database called "distro_zone"');
        $this->info('3. Update your .env file with correct database credentials');
        $this->info('4. Run the following commands:');
        $this->line('');
        $this->info('   php artisan migrate --seed');
        $this->info('   php artisan db:seed');
        $this->info('   php artisan storage:link');
        $this->line('');
        $this->info('5. To run the application:');
        $this->info('   php artisan serve');
        $this->line('');
        $this->info('6. Access the admin panel at:');
        $this->info('   http://127.0.0.1:8000/admin');
        $this->line('');
        $this->info('Note: The application has been fully configured with:');
        $this->info('- Dashboard with charts (sales, best selling products, low stock)');
        $this->info('- Master data management (brands, products, types)');
        $this->info('- Settings management (operational hours, users)');
        $this->info('- AdminLTE frontend theme');
        $this->info('- Proper admin navigation menu');
    }
}
