<?php

    namespace src\controllers;

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    use src\models\User as UserModel;

    use src\middleware\Auth;

    class User
    {

        private $content = array();
        private $httpStatusCode = 406;

        public function cadastro(Request $request, Response $response, array $args): Response{

            try {
                if(!isset($request->getParsedBody()['email']))
                    throw new \Exception("Informe um email");
                if(empty($request->getParsedBody()['email']))
                    throw new \Exception("Email vazio");

                if(!isset($request->getParsedBody()['name']))
                    throw new \Exception("Informe um nome");
                if(empty($request->getParsedBody()['name']))
                    throw new \Exception("Nome vazio");

                if(!isset($request->getParsedBody()['password']))
                    throw new \Exception("Informe a senha");
                if(empty($request->getParsedBody()['password'])){
                    throw new \Exception("Senha vazia");
                }
                else

                    $user = new UserModel();
                $user->setEmail($request->getParsedBody()['email']);
                $user->setPassword($request->getParsedBody()['password']);
                $user->setNome($request->getParsedBody()['name']);

                $this->content['message'] = $user->cadastro();
                $this->httpStatusCode = 200;

            }catch (\Exception $e){
                $this->content['message'] = $e->getMessage();
            }
            return $response->withJSON($this->content)->withStatus($this->httpStatusCode);

        }

        public function login(Request $request, Response $response, array $args): Response{
            try {

                if(!isset($request->getParsedBody()['email']))
                    throw new \Exception("Informe um email");
                if(empty($request->getParsedBody()['email']))
                    throw new \Exception("Email vazio");

                if(!isset($request->getParsedBody()['password']))
                    throw new \Exception("Informe a senha");
                if(empty($request->getParsedBody()['password'])){
                    throw new \Exception("Senha vazia");
                }
                else

                $user = new UserModel();
                $user->setEmail($request->getParsedBody()['email']);
                $user->setPassword($request->getParsedBody()['password']);
                $user->setExpo($request->getParsedBody()['expo']);

                $auth = new Auth();
                $dadosUser = $user->login();

                unset($dadosUser['password']);
                $payload = $dadosUser;

                $this->content['user'] = $payload;
                $this->content['token'] = $auth->generateToken($payload);
                $this->httpStatusCode = 200;

            } catch (\Exception $e) {
                $this->content['message'] = $e->getMessage();
            }
            return $response->withJSON($this->content)->withStatus($this->httpStatusCode);
        }


        public function dashboard(Request $request, Response $response, array $args) : Response{

            try{

                $token = ($request->getHeader('HTTP_AUTHORIZATION'));
                $explode = explode(" ",$token[0]);
                $token_final = ($explode[1]);

                $productModel = new UserModel();
                $this->content['dashboard'] = $productModel->dashboard($token_final);
                $this->httpStatusCode = 200;

            }catch(\Exception $e){
                $this->content['message'] = $e->getMessage();
            }
            return $response->withJSON($this->content)->withStatus($this->httpStatusCode);
        }




        public function refresh(Request $request, Response $response, array $args):Response{

            try{

                if(empty($request->getParsedBody()['instalacao']))
                    throw new \Exception("Instalacao vazio");
                if(!isset($request->getParsedBody()['instalacao']))
                    throw new \Exception("Informe a Instalação");


                if(empty($request->getParsedBody()['caixa']))
                    throw new \Exception("Caixa vazio");
                if(!isset($request->getParsedBody()['caixa'])){
                    throw new \Exception("Informe a Caixa");

                }
                else

                $infos = new UserModel();
                $infos->setInstalacao($request->getParsedBody()['instalacao']);
                $infos->setRua($request->getParsedBody()['rua']);
                $infos->setVazamento($request->getParsedBody()['vazamento']);
                $infos->setCaixa($request->getParsedBody()['caixa']);

                $this->content['message'] = $infos->refresh();
                $this->httpStatusCode = 200;

            }catch (\Exception $e){
                $this->content['message'] = $e->getMessage();
            }
            return $response->withJSON($this->content)->withStatus($this->httpStatusCode);
        }


        public function notificacao(Request $request, Response $response, array $args):Response{


            $infos = new UserModel();

            $notificacao= $infos->send();
            $this->httpStatusCode = 200;

            return $response->withJSON($notificacao)->withStatus($this->httpStatusCode);



        }




        }
