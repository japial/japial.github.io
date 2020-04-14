<?php

Route::get('/clear-cache', function() {
   $exitCode = Artisan::call('cache:clear');
   $exitCode = Artisan::call('config:clear');
   $exitCode = Artisan::call('route:clear');
   $exitCode = Artisan::call('view:clear');
   // return what you want
});
