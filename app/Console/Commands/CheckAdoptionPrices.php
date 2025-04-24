<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adoption\Adoption;
use App\Models\Adoption\AdoptionStock;
use App\Models\Adoption\AdoptionCurrency;
use App\Models\Adoption\AdoptionPrice;
use Carbon\Carbon;

class CheckAdoptionPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-adoption-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and updates the prices of adoption stock as defined in the adoption center.';

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

        $this->info('***************************');
        $this->info('* UPDATE ADOPTION PRICES *');
        $this->info('***************************'."\n");

        // get all the currently available adoption stocks
        $adoptionStocks = AdoptionStock::visible()->get();

        // create a map of Adoptionprices by day for easier access
        $pricesByDays = AdoptionPrice::all()->groupBy('days'); 

        // for each stock check how many days old it is and update prices accordingly.
        foreach($adoptionStocks as $stock){
            $this->info('--------------');
            if($stock->created_at){
                $stockDays = $stock->created_at->diffInDays(Carbon::now());
                $this->info('Checking stock: ' . $stock->character->slug . '. Age: ' . $stockDays . ' days. Species: ' . ($stock->character->image->species != null ? $stock->character->image->species->name : 'None'));
                // we set all currencies even if there are multiple for a single day!
                foreach($pricesByDays as $days => $prices){
                    if($stockDays >= $days){
                        // first delete previous prices from the stock
                        $stock->currency()->delete();
                        foreach($prices as $price){
                            // then set all new prices if species of stock matches or no price species is set
                            if(($stock->character->image->species != null && $stock->character->image->species->id == $price->species_id) || $price->species_id == null){
                                $this->info( $days . ' days reached. Set to: ' . $price->amount . ' ' . $price->currency->name);
                                AdoptionCurrency::create([
                                    'stock_id'       => $stock->id,
                                    'currency_id' => $price->currency_id,
                                    'cost'   => $price->amount,
                                ]);
                            }
                        }
                    } else {
                        $this->info('Stock is too recent for a day '. $days .' price update, moving on...');
                    }
                }
            } else {
                $this->info('Stock: ' . $stock->character->slug . ' is missing created_at. Skipping.');
            }
           
        }

    }
}
