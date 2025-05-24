<?php

Route::prefix('drivers')->group(function () {

    foreach (["auth.php", "orders.php" ,  'status.php'] as $value) {
        require(module_path('DriverApp', 'Routes/Api/' . $value));
    }

});
