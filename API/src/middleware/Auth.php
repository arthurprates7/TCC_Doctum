<?php

    namespace src\middleware;

    use Firebase\JWT\JWT;
    use Tuupola\Middleware\JwtAuthentication;

    class Auth{

        private $key = "177762083c8e2fbe8ed18d058bc8a108c4fb13db";

        public function generateToken($payload){

            $payload['expirate_at'] = date("Y-m-d H:i:s", strtotime('+1000000 minutes'));
            return JWT::encode( $payload, getenv("JWT_SECRET_KEY") );

        }

        public function decodeToken($token){
            return JWT::decode($token, getenv("JWT_SECRET_KEY"),array('HS256'));
        }


        public function validateToken() : JwtAuthentication {

            return new JwtAuthentication([

                'secure' => false,
                'secret' => getenv("JWT_SECRET_KEY"),
                'attribute' => 'jwt',
                "error" => function ($response, $arguments) {

                    if($arguments['message'] == "Token not found.")
                        $arguments['message'] = "Token não encontrado";

                    if($arguments['message'] == "Wrong number of segments")
                        $arguments['message'] = "Número de segmentos está errado";

                    if($arguments['message'] == "Syntax error, malformed JSON")
                        $arguments['message'] = "Erro de sintaxe. JSON mal-formado";

                    if($arguments['message'] == "Signature verification failed")
                        $arguments['message'] = "A assinatura de verificação falhou";

                    $data["message"] = $arguments["message"];
                    return $response
                        ->withHeader("Content-Type", "application/json")
                        ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }



            ]);

        }

        public function validateData($data){

            $dataAtual = strtotime(date("Y-m-d H:i:s"));
            $dataToken = strtotime($data);

            if($dataToken < $dataAtual)
                throw new \Exception("Token já expirou! Fazer Novo Login!");


        }

    }
