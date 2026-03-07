<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPostsIsActive extends Command
{
    protected $signature = 'fix:posts-is-active';
    protected $description = 'Add is_active column to posts table in all tenant databases';

    public function handle()
    {
        // Fix temp tenant database
        $this->info("Processing tenant_temp.sqlite...");
        try {
            $pdo = new \PDO('sqlite:' . database_path('tenant_temp.sqlite'));
            $pdo->exec('ALTER TABLE posts ADD COLUMN is_active INTEGER DEFAULT 1 NOT NULL');
            $this->info("  ✓ is_active column added to tenant_temp.sqlite");
        } catch (\PDOException $e) {
            if (str_contains($e->getMessage(), 'duplicate column')) {
                $this->line("  ℹ is_active column already exists in tenant_temp.sqlite");
            } else {
                $this->error("  ✗ Error: " . $e->getMessage());
            }
        }
        
        // Fix registered tenants
        $tenants = Tenant::all();
        
        $this->info("\nProcessing {$tenants->count()} registered tenants...");
        
        foreach ($tenants as $tenant) {
            $this->line("  Processing tenant: {$tenant->id}");
            
            $tenant->run(function () {
                try {
                    DB::statement('ALTER TABLE posts ADD COLUMN is_active INTEGER DEFAULT 1 NOT NULL');
                    $this->info("    ✓ is_active column added");
                } catch (\Exception $e) {
                    if (str_contains($e->getMessage(), 'duplicate column')) {
                        $this->line("    ℹ is_active column already exists");
                    } else {
                        $this->error("    ✗ Error: " . $e->getMessage());
                    }
                }
            });
        }
        
        $this->info("\nDone!");
        return 0;
    }
}
