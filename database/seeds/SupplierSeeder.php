<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Supplier\Entities\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {

            $count = Supplier::count();
            $data = [
                'image' => path_without_domain(url('storage/photos/shares/suppliers/1.png')),
                'status' => 1,
            ];

            if ($count == 0) {
                for ($i = 0; $i < 10; $i++) {
                    Supplier::create($data);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

}
