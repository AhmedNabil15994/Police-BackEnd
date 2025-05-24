<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
        INSERT INTO `categories` (`id`, `image`, `slug`, `status`, `show_in_home`, `category_id`, `sort`, `deleted_at`, `created_at`, `updated_at`) VALUES
                    (1, '/storage/photos/shares/logo/logo.png', NULL, 1, 0, NULL, 1, NULL, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
                    (2, '/storage/photos/shares/categories/1.png', NULL, 1, 1, NULL, 2, NULL, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
                    (3, '/storage/photos/shares/categories/2.png', NULL, 1, 1, NULL, 3, NULL, '2020-10-07 23:13:21', '2020-10-07 23:15:26'),
                    (4, '/storage/photos/shares/categories/3.png', NULL, 1, 1, NULL, 4, NULL, '2020-10-07 23:14:56', '2020-10-07 23:14:56'),
                    (5, '/storage/photos/shares/categories/4.png', NULL, 1, 1, NULL, 5, NULL, '2020-10-07 23:16:41', '2020-10-07 23:16:41'),
                    (6, '/storage/photos/shares/categories/5.png', NULL, 1, 1, NULL, 6, NULL, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
                    (7, '/storage/photos/shares/categories/6.png', NULL, 1, 1, NULL, 7, NULL, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
                    (8, '/storage/photos/shares/categories/2.png', NULL, 1, 1, NULL, 8, NULL, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
                    (9, '/storage/photos/shares/categories/1.png', NULL, 1, 1, NULL, 9, NULL, '2020-10-07 23:17:10', '2020-10-07 23:17:10');
        ");

        DB::statement("
        INSERT INTO `category_translations` (`id`, `slug`, `title`, `seo_keywords`, `seo_description`, `locale`, `category_id`, `created_at`, `updated_at`) VALUES
            (1, 'main-category', 'Main Category', NULL, NULL, 'en', 1, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
            (2, 'qasam-rayiysaa', 'قسم رئيسى', NULL, NULL, 'ar', 1, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
            (3, 'meals', 'Meals', NULL, NULL, 'en', 2, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
            (4, 'alwajabat', 'الوجبات', NULL, NULL, 'ar', 2, '2020-10-07 23:12:16', '2020-10-07 23:12:16'),
            (5, 'sandwiches', 'Sandwiches', NULL, NULL, 'en', 3, '2020-10-07 23:13:21', '2020-10-07 23:13:21'),
            (6, 'alsndwtshat', 'السندوتشات', NULL, NULL, 'ar', 3, '2020-10-07 23:13:21', '2020-10-07 23:13:21'),
            (7, 'appetizers', 'Appetizers', NULL, NULL, 'en', 4, '2020-10-07 23:14:56', '2020-10-07 23:14:56'),
            (8, 'almuqbilat', 'المقبلات', NULL, NULL, 'ar', 4, '2020-10-07 23:14:56', '2020-10-07 23:14:56'),
            (9, 'drinks', 'Drinks', NULL, NULL, 'en', 5, '2020-10-07 23:16:41', '2020-10-07 23:16:41'),
            (10, 'almashrubat', 'المشروبات', NULL, NULL, 'ar', 5, '2020-10-07 23:16:41', '2020-10-07 23:16:41'),
            (11, 'sweets', 'Sweets', NULL, NULL, 'en', 6, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (12, 'alhulawayat', 'الحلويات', NULL, NULL, 'ar', 6, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (13, 'pancakes', 'Pancakes', NULL, NULL, 'en', 7, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (14, 'alfatayir', 'الفطائر', NULL, NULL, 'ar', 7, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (15, 'family-meals', 'Family meals', NULL, NULL, 'en', 8, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (16, 'alwajabat-aleayilia', 'الوجبات العائلية', NULL, NULL, 'ar', 8, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (17, 'grills', 'Grills', NULL, NULL, 'en', 9, '2020-10-07 23:17:10', '2020-10-07 23:17:10'),
            (18, 'almashaway', 'المشاوى', NULL, NULL, 'ar', 9, '2020-10-07 23:17:10', '2020-10-07 23:17:10');
        ");
    }

}
