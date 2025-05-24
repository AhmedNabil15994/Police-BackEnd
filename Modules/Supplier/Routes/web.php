<?php


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    foreach (["supplier.php"] as $value) {
        require_once(module_path('Supplier', 'Routes/Dashboard/' . $value));
    }

});
