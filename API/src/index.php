<?php
    require_once "../.vendor/autoload.php";
    require_once "../src/config/env.php";
    
    $app = new Slim\App(

        [

            'settings' =>[

                'displayErrorDetails' => true,

            ],

        ]


    );
    
    require_once "./routes/user.php";
    require_once "./routes/product.php";

    $app->run();

    
?>