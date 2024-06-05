<?php

/*
 * This file is part of the yoeunes/toastr package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

return array(
    /*
    |--------------------------------------------------------------------------
    | Toastr options
    |--------------------------------------------------------------------------
    |
    | Here you can specify the options that will be passed to the toastr.js
    | library. For a full list of options, visit the documentation.
    |
    | Example:
    | 'options' => [
    |     'closeButton' => true,
    |     'debug' => false,
    |     'newestOnTop' => false,
    |     'progressBar' => true,
    | ],
    */

    'options' => array(

        'closeButton' => true,
        'newestOnTop' => false,
        'progressBar' => true,
        'positionClass' => "toast-top-right",
        'preventDuplicates' => false,
        'onclick' => null,
        'showDuration' => "300",
        'hideDuration' => "1000",
        'timeOut' => "5000",
        'extendedTimeOut' => "1000",
        'showEasing' => "swing",
        'hideEasing' => "linear",
        'showMethod' => "fadeIn",
        'hideMethod' => "fadeOut"

    ),
);
