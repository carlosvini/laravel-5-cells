<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Cells for Laravel 4 Config
    |--------------------------------------------------------------------------
    */

    // Disable caching in local env
    'disable_cache_in_dev' => false,
   
   
    'paths' => [
        realpath(base_path('resources/cells'))
    ],
);
