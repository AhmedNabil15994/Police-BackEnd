<?php

use Illuminate\Database\Seeder;
use Modules\Catalog\Entities\Product;
use Modules\Vendor\Entities\Vendor;

class AssignProductsToVendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = Vendor::whereNull('parent_id')->get();
        $products = Product::pluck('id')->toArray();

        if ($providers && count($products) > 0) {
            foreach ($providers as $k => $provider) {
                $mainBranch = Vendor::where('parent_id', $provider->id)->where('is_main_branch', 1)->first();
                $provider->providerProducts()->attach($products, ['vendor_id' => $mainBranch->id]);
            }
        }

    }
}
