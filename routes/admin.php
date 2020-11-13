<?php 
Route::apiResource('typepayments', 'Payments\PaymentController');
Route::apiResource('zonas','ZonaLaserController')->parameters(['zonas'=>'zonaLaser']);
?>