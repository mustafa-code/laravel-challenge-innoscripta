<?php

namespace App\Http\Controllers;

use Error;
use Exception;

class GeneralController extends Controller
{
    public function index() {
        try{
            $php_path = shell_exec("which php");
        } catch (Error | Exception $e){
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
    }
}
