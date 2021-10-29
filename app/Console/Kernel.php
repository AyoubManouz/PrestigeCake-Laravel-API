<?php

namespace App\Console;

use App\Models\Produit;
use App\Models\Promotion;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use function Complex\add;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $promotions = Promotion::whereDate('dateFinPromo', '<', today())->get();
            foreach ($promotions as $promotion) {
                $produits = Produit::query()->with('promotion')->where('promotion_id', $promotion->id)->get();
                foreach ($produits as $produit) {
                    $produit->promotion_id = null;
                    $produit->save();
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
