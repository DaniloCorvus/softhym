<?php

namespace App\Console\Commands;

use App\Traits\CierreCajaTraits;
use Illuminate\Console\Command;


class Cierrecajas extends Command
{

    use CierreCajaTraits;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cierre:cajas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierre de cajas ';

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
     * @return int
     */
    public function handle()
    {

        $this->guardarCierre();
        $this->info('Cajas cerradas correctamente ');
    }
}
