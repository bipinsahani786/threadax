<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = \App\Models\Product::first();

if ($product) {
    $product->video_url = 'https://www.youtube.com/watch?v=LXb3EKWsInQ'; // Sample video (4K nature)
    $product->model_3d_url = 'https://modelviewer.dev/shared-assets/models/Astronaut.glb'; // Sample 3D Model
    $product->save();
    echo "Updated product '{$product->name}' with Video and 3D Model URLs.\n";
} else {
    echo "No products found.\n";
}
