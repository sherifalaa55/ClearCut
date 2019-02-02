<?php

namespace SherifAI\ClearCut\Commands;

use Illuminate\Console\Command;

class MigrationCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clearcut:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Lever specifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $app = app();
        $app['view']->addNamespace('clearcut', substr(__DIR__, 0, -8).'views');
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('');
        $this->info('The migration process will create a migration file for the request logs table');

        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]")) {
            $this->line('');

            $this->info("Creating migration...");
            if ($this->createMigration('request_logs')) {
                $this->line('');

                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Coudn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }

            $this->line('');
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Create the migration
     *
     * @param  string $name
     * @return bool
     */
    protected function createMigration()
    {
        //Create the migration
        $app = app();
        $migrationFiles = [
            $this->laravel->path."/../database/migrations/*_setup_request_logs_table.php" => 'clearcut::migration',
        ];

        $seconds = 0;

        foreach ($migrationFiles as $migrationFile => $outputFile) {

            if (sizeof(glob($migrationFile)) == 0) {
                $migrationFile = str_replace('*', date('Y_m_d_His', strtotime('+' . $seconds . ' seconds')), $migrationFile);

                $fs = fopen($migrationFile, 'x');
                if ($fs) {
                    $output = "<?php\n\n" .$app['view']->make($outputFile)->render();

                    fwrite($fs, $output);
                    fclose($fs);
                } else {
                    return false;
                }

                $seconds++;
            }
        }

        //Create the seeder

        return true;
    }
    
    /**
     * BC for older laravel versions
     */
    public function fire()
    {
        $this->handle();
    }
}
