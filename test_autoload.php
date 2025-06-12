<?php

require __DIR__.'/vendor/autoload.php';

echo "Checking HasFactory trait:\n";
var_dump(trait_exists('Illuminate\Database\Eloquent\Factories\HasFactory'));

echo "\nFile exists: ";
var_dump(file_exists(__DIR__.'/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php'));