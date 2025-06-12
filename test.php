<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Database\Eloquent\Factories\HasFactory;

// Проверка трейта
echo "Trait exists: " 
    . (trait_exists(HasFactory::class) ? 'YES' : 'NO') 
    . PHP_EOL;

// Проверка в модели
if (class_exists(App\Models\YourModel::class)) {
    $model = new App\Models\YourModel;
    echo "Model uses HasFactory: " 
        . (in_array(HasFactory::class, class_uses($model)) ? 'YES' : 'NO');
}