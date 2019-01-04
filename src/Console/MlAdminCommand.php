<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;

class MlAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all ml admin commands';

    public static $logo = <<<LOGO
    ___ ___      __                  __          _     
   /  __ __ \   / /       ____ _____/ /___ ___  (_)___ 
  / / / / / /  / /  _____/ __ `/ __  / __ `__ \/ / __ \
 / / / / / /  / /__/____/ /_/ / /_/ / / / / / / / / / /
/_/ /_/ /_/  /_____/    \__,_/\__,_/_/ /_/ /_/_/_/ /_/ 
                                                                          
LOGO;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
         $this->line(static::$logo);
    }
}
