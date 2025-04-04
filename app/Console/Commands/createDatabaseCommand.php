<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class createDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:database {name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create a database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            $this->error("The database name is not valid.");
            return self::FAILURE;
        }

        try {
            $exist = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",[$name]);
            if ($exist) {
                $this->info("The {$name} database already exists.");
            } else {
                DB::statement("CREATE DATABASE IF NOT EXISTS {$name}");
                $this->info("The {$name} database was created successfully.");
            }
            return self::SUCCESS;
        }catch(\Exception $e) {
            $this->info("Failed to create database: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
