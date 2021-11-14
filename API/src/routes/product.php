<?php

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    use src\controllers\User;
    use src\middleware\Auth;
    use src\middleware\JWTdate;

    $app->group("/all", function() use ($app){


        $app->get("/dashboard", User::class.":dashboard");
        $app->post("/controlemensal", User::class.":controle");



    })
    ->add(new JWTdate)
    ->add(Auth::validateToken());

