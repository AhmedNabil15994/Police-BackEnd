<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
            INSERT INTO `vendor_statuses` (`id`, `flag`, `accepted_orders`, `label_color`, `created_at`, `updated_at`) VALUES
            (1, 'open', 1, 'success', '2020-06-16 12:58:02', '2020-06-16 12:58:02'),
            (3, 'closed', 0, 'danger', '2020-06-16 13:02:25', '2020-06-16 13:02:25'),
            (4, 'busy', 0, 'warning', '2020-06-16 13:02:45', '2020-06-16 13:02:45');
        ");

        DB::statement("
            INSERT INTO `vendor_status_translations` (`id`, `title`, `locale`, `vendor_status_id`, `created_at`, `updated_at`) VALUES
            (1, 'Open', 'en', 1, '2020-06-16 12:58:02', '2020-06-16 12:58:02'),
            (2, 'مفتوح', 'ar', 1, '2020-06-16 12:58:02', '2020-06-16 12:58:02'),
            (5, 'Closed', 'en', 3, '2020-06-16 13:02:25', '2020-06-16 13:02:25'),
            (6, 'مغلق', 'ar', 3, '2020-06-16 13:02:25', '2020-06-16 13:02:25'),
            (7, 'Busy', 'en', 4, '2020-06-16 13:02:45', '2020-06-16 13:02:45'),
            (8, 'مشغول', 'ar', 4, '2020-06-16 13:02:45', '2020-06-16 13:02:45');
        ");

        DB::statement("
            INSERT INTO `vendors` (`id`, `parent_id`, `is_main_branch`, `order_limit`, `fixed_delivery`, `image`, `sorting`, `is_trusted`, `commission`, `fixed_commission`, `status`, `supplier_code_myfatorah`, `receive_question`, `vendor_email`, `receive_prescription`, `vendor_status_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, null, null, null, null, 'storage/photos/shares/vendors/default.jpg', 0, 1, Null, NULL, 1, NULL, 0, 'core-restaurants@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09'),
            (2, 1, 1,'1.000', '2.000', 'storage/photos/shares/vendors/default.jpg', 0, 1, 1, NULL, 1, NULL, 0, 'salmiya@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09'),
            (3, 1, 0,'1.000', '2.000', 'storage/photos/shares/vendors/default.jpg', 0, 1, 1, NULL, 1, NULL, 0, 'ahmadi@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09'),
            (4, 1, 0,'1.000', '2.000', 'storage/photos/shares/vendors/default.jpg', 0, 1, 1, NULL, 1, NULL, 0, 'mubarak_al_kabeer@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09'),
            (5, 1, 0,'1.000', '2.000', 'storage/photos/shares/vendors/default.jpg', 0, 1, 1, NULL, 1, NULL, 0, 'al_jahra@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09'),
            (6, 1, 0,'1.000', '2.000', 'storage/photos/shares/vendors/default.jpg', 0, 1, 1, NULL, 1, NULL, 0, 'sharq@gmail.com', 0, 1, NULL, '2020-08-14 11:13:48', '2020-08-22 22:47:09');

        ");

        DB::statement("
            INSERT INTO `vendor_translations` (`id`, `slug`, `title`, `description`, `seo_keywords`, `seo_description`, `locale`, `vendor_id`, `created_at`, `updated_at`) VALUES
            (1, 'police-steak-restaurant', 'Police Steak', 'Police Steak', 'Police Steak', 'Police Steak', 'en', 1, NULL, NULL),
            (2, 'mateam-police-steak', 'مطعم Police Steak', 'مطعم Police Steak', 'مطعم Police Steak', 'مطعم Police Steak', 'ar', 1, NULL, NULL),
            (3, 'salmiya-branch', 'Salmiya Branch', 'Salmiya Branch', 'Salmiya Branch', 'Salmiya Branch', 'en', 2, NULL, NULL),
            (4, 'farae-alssalimia', 'فرع السالمية', 'فرع السالمية', 'فرع السالمية', 'فرع السالمية', 'ar', 2, NULL, NULL),
            (5, 'ahmadi-branch', 'Ahmadi Branch', 'Ahmadi Branch', 'Ahmadi Branch', 'Ahmadi Branch', 'en', 3, NULL, NULL),
            (6, 'firae-alahmadi', 'فرع الأحمدي', 'فرع الأحمدي', 'فرع الأحمدي', 'فرع الأحمدي', 'ar', 3, NULL, NULL),
            (7, 'mubarak-al-kabeer-branch', 'Mubarak Al-Kabeer Branch', 'Mubarak Al-Kabeer Branch', 'Mubarak Al-Kabeer Branch', 'Mubarak Al-Kabeer Branch', 'en', 4, NULL, NULL),
            (8, 'firae-mubarak-alkabir', 'فرع مبارك الكبير', 'فرع مبارك الكبير', 'فرع مبارك الكبير', 'فرع مبارك الكبير', 'ar', 4, NULL, NULL),
            (9, 'al-jahra-branch', 'Al-Jahra Branch', 'Al-Jahra Branch', 'Al-Jahra Branch', 'Al-Jahra Branch', 'en', 5, NULL, NULL),
            (10, 'firae-aljuhra', 'فرع الجهراء', 'فرع الجهراء', 'فرع الجهراء', 'فرع الجهراء', 'ar', 5, NULL, NULL),
            (11, 'sharq-branch', 'Sharq Branch', 'Sharq Branch', 'Sharq Branch', 'Sharq Branch', 'en', 6, NULL, NULL),
            (12, 'firae-shrq', 'فرع شرق', 'فرع شرق', 'فرع شرق', 'فرع شرق', 'ar', 6, NULL, NULL);

        ");

        DB::statement("
            INSERT INTO `vendor_sellers` (`id`, `vendor_id`, `seller_id`, `created_at`, `updated_at`) VALUES (NULL, '2', '42', NULL, NULL);
        ");

        DB::statement("
            INSERT INTO `subscriptions` (`id`, `original_price`, `total`, `start_at`, `end_at`, `status`, `send_expiration_at`, `package_id`, `vendor_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (NULL, '50', '120', '2020-09-01', '2030-09-30', '1', '2030-09-29 18:12:25', '3', '2', NULL, NULL, NULL);
        ");
    }

}
