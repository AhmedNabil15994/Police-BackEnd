<?php

namespace Modules\Catalog\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Catalog\Repositories\Dashboard\ProductRepository;
use Modules\Catalog\Entities\Product as Product;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Vendor\Traits\VendorTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class CheckProductAvailabilitiesCommand extends Command
{
    use CatalogTrait, VendorTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'product:checkAvailability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check product availabilities and clear cache in product times';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateNow = Carbon::now();
        $currentTime = $dateNow->format('H:i');
        $currentDayCode = Str::lower($dateNow->format('D'));

        $products = $this->product->active();
        if (!is_null($this->getSingleVendor())) {
            $products = $this->defaultVendorCondition($products, $this->getSingleVendor()->id);
        }

        $products = $products->whereHas('workingTimes', function ($query) use ($currentTime, $currentDayCode) {
            $query->where(function ($query) use ($currentTime, $currentDayCode) {
                $query->where('day_code', $currentDayCode)->where('status', 1)->where('is_full_day', 0);
                $query->whereHas('workingTimeDetails', function ($query) use ($currentTime, $currentDayCode) {
                    $query->where('time_from', '<=', date("H:i:s", strtotime($currentTime)));
                    $query->where('time_to', '>=', date("H:i:s", strtotime($currentTime)));
                });
            });

            $query->orWhere(function ($query) use ($currentDayCode) {
                $query->where('day_code', $currentDayCode)->where('status', 1)->where('is_full_day', 1);
            });
        });
        $products = $products->pluck('id')->toArray();

        /* if (count($products) > 0) {
            foreach ($products as $k => $product) {
            }
        } */

        $this->info('Command Executed Successfully!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    /*protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }*/

    /**
     * Get the console command options.
     *
     * @return array
     */
    /*protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }*/
}
