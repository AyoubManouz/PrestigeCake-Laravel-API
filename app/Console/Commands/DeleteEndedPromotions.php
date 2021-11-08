<?php

namespace App\Console\Commands;

use App\Models\Produit;
use App\Models\Promotion;
use Illuminate\Console\Command;

class DeleteEndedPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deleteEndedPromotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $promotions = Promotion::whereDate('dateFinPromo', '<', today())->get();
        foreach ($promotions as $promotion) {
            $produits = Produit::query()->with('promotion')->where('promotion_id', $promotion->id)->get();
            foreach ($produits as $produit) {
                $produit->promotion_id = null;
                $produit->save();
            }
        }
        echo("Promotions are deleted");
    }
}
