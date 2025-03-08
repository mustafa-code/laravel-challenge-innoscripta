<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['verify.signature'])->group(function () {
});

Route::get("cron-job", function(){
    try{
        $php_path = shell_exec("which php");
    } catch (Error $e){
        $php_path = "/usr/local/bin/php";
    }

    $command = base_path() . '/artisan schedule:run >> /dev/null 2>&1';
    $cpanel_command = $php_path . ' ' . base_path() . '/artisan schedule:run >> /dev/null 2>&1';
    $settings = [
        'php_path' => $php_path,
        'cpanel_command' => $cpanel_command,
        'command' => $command,
    ];
    return response()->success(
        data: $settings,
    );
});
