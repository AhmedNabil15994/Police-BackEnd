<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Slider\Entities\Slider;

class SliderSeeder extends Seeder
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

            $all = [
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'image' => path_without_domain(url('storage/photos/shares/sliders/1.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'اطلب الان من ' . env('APP_NAME'),
                        'en' => 'Order Now From ' . env('APP_NAME'),
                    ],
                    'short_description' => [
                        'ar' => 'مطعم ' . env('APP_NAME') . ' ، مأكولات ومشروبات',
                        'en' => env('APP_NAME') . ', Foods, Drinks',
                    ],
                ],
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'image' => path_without_domain(url('storage/photos/shares/sliders/2.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'اطلب الان من ' . env('APP_NAME'),
                        'en' => 'Order Now From ' . env('APP_NAME'),
                    ],
                    'short_description' => [
                        'ar' => 'مطعم ' . env('APP_NAME') . ' ، مأكولات ومشروبات',
                        'en' => env('APP_NAME') . ', Foods, Drinks',
                    ],
                ],
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'image' => path_without_domain(url('storage/photos/shares/sliders/3.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'اطلب الان من ' . env('APP_NAME'),
                        'en' => 'Order Now From ' . env('APP_NAME'),
                    ],
                    'short_description' => [
                        'ar' => 'مطعم ' . env('APP_NAME') . ' ، مأكولات ومشروبات',
                        'en' => env('APP_NAME') . ', Foods, Drinks',
                    ],
                ],
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'image' => path_without_domain(url('storage/photos/shares/sliders/4.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'اطلب الان من ' . env('APP_NAME'),
                        'en' => 'Order Now From ' . env('APP_NAME'),
                    ],
                    'short_description' => [
                        'ar' => 'مطعم ' . env('APP_NAME') . ' ، مأكولات ومشروبات',
                        'en' => env('APP_NAME') . ', Foods, Drinks',
                    ],
                ],
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'image' => path_without_domain(url('storage/photos/shares/sliders/5.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'اطلب الان من ' . env('APP_NAME'),
                        'en' => 'Order Now From ' . env('APP_NAME'),
                    ],
                    'short_description' => [
                        'ar' => 'مطعم ' . env('APP_NAME') . ' ، مأكولات ومشروبات',
                        'en' => env('APP_NAME') . ', Foods, Drinks',
                    ],
                ],
            ];

            $count = Slider::count();

            if ($count == 0) {
                foreach ($all as $k => $slider) {
                    $translations['title'] = $slider['title'];
                    $translations['short_description'] = $slider['short_description'];
                    unset($slider['title']);
                    unset($slider['short_description']);

                    $s = Slider::create($slider);
                    $this->translateTable($s, $translations);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function translateTable($model, $translations)
    {
        foreach ($translations['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
            $model->translateOrNew($locale)->short_description = $translations['short_description'][$locale];
        }
        $model->save();
    }

}
