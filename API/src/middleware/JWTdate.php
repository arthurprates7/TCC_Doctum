<?php

    namespace src\middleware;


    class JWTdate{

            public function __invoke($request,$response,$next){

                $token = $request->getAttribute('jwt');
                $data_expiracao = new \DateTime($token['expirate_at']);

                $agora = new \DateTime();

                if($data_expiracao<$agora){

                    return $response->withJson([
                        "message"=>"Data do Token Invalida"
                    ])->withStatus(401);

                }

                $response = $next($request,$response);
                return $response;


            }


    }
