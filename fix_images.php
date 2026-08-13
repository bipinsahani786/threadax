<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\ProductImage::all() as $i => $img) {
    $img->url = asset('storage/products/' . (($i % 5) + 1) . '.png');
    $img->save();
    echo "Updated {$img->id}\n";
}
