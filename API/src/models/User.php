<?php

    namespace src\models;
    use src\config\Connection;
    use src\middleware\Auth;

    class User extends Connection{

        private $nome;
        private $email;
        private $password;
        private $mes;
        private $expo;
        private $instalacao;
        private $rua;
        private $vazamento;
        private $caixa;


        /**
         * @return mixed
         */
        public function getCaixa()
        {
            return $this->caixa;
        }

        /**
         * @return mixed
         */
        public function getInstalacao()
        {
            return $this->instalacao;
        }

        /**
         * @return mixed
         */
        public function getRua()
        {
            return $this->rua;
        }

        /**
         * @return mixed
         */
        public function getVazamento()
        {
            return $this->vazamento;
        }

        /**
         * @param mixed $caixa
         */
        public function setCaixa($caixa): void
        {
            $this->caixa = $caixa;
        }

        /**
         * @param mixed $instalacao
         */
        public function setInstalacao($instalacao): void
        {
            $this->instalacao = $instalacao;
        }

        /**
         * @param mixed $rua
         */
        public function setRua($rua): void
        {
            $this->rua = $rua;
        }

        /**
         * @param mixed $vazamento
         */
        public function setVazamento($vazamento): void
        {
            $this->vazamento = $vazamento;
        }


        /**
         * @return mixed
         */
        public function getNome()
        {
            return $this->nome;
        }

        /**
         * @param mixed $nome
         */
        public function setNome($nome): void
        {
            $this->nome = $nome;
        }

        /**
         * @return mixed
         */
        public function getExpo()
        {
            return $this->expo;
        }

        /**
         * @param mixed $expo
         */
        public function setExpo($expo): void
        {
            $this->expo = $expo;
        }


        /**
         * @return mixed
         */
        public function getMes()
        {
            return $this->mes;
        }

        /**
         * @param mixed $mes
         */
        public function setMes($mes): void
        {
            $this->mes = $mes;
        }


        public function __construct(){
            parent::__construct();
        }


        public function setEmail($email){
            $this->email = $email;
        }

        public function setPassword($password){
            $this->password = $password;
        }

        public function getEmail(){
            return $this->email;
        }

        public function getPassword(){
            return $this->password;
        }



        public function login(){
            $email = $this->getEmail();
            $password = $this->getPassword();
            $expo = $this->getExpo();

            $sql = $this->pdo->prepare(
                "UPDATE `users` SET `expo_token` = :expo WHERE `users`.`email` = :email;"
            );

            $sql->bindParam(":expo", $expo);
            $sql->bindParam(":email", $email);

            $sql->execute();

            $sql = $this->pdo->prepare(
                "SELECT id, name, password FROM users WHERE email = :email LIMIT 1"
            );

            $sql->bindParam(":email", $email);
            $sql->execute();

            if($sql->rowCount() !== 1)
                throw new \Exception("Usuário ou senha inválidos");

            $result = $sql->fetchAll(\PDO::FETCH_ASSOC)[0];

            if(!password_verify($password, $result['password']))
                throw new \Exception('Usuário ou senha inválidos');

            return $result;
        }






        public function dashboard($token_final){

            $dados = [];

            $auth = new Auth();

            $token = $auth->decodeToken($token_final);

            $usuario_id = ($token->id);

            $sql = $this->pdo->prepare(
                "SELECT name FROM `rua` 
                        INNER JOIN users ON
                        users.id = rua.user
                        where users.id =$usuario_id");

            $sql->execute();

            if($sql->rowCount() < 1)
                throw new \Exception("Erro ao obter informações");

            $usuario = $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['name'];



            $sql = $this->pdo->prepare(
                "SELECT id FROM `users` 
                        where users.id =$usuario_id");
            $sql->execute();

            if($sql->rowCount() < 1)
                throw new \Exception("Erro na API");

            $instalacao = $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['id'];


            $sql = $this->pdo->prepare(
                "SELECT caixa FROM `caixa` 
                        INNER JOIN users ON
                        users.id = caixa.user
                        where users.id =$usuario_id ORDER BY caixa.id DESC LIMIT 1");
            $sql->execute();

            if($sql->rowCount() < 1)
                throw new \Exception("Erro na API");

            $caixa = $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['caixa'];


            $sql = $this->pdo->prepare(
                "SELECT vazamento FROM `caixa` 
                        INNER JOIN users ON
                        users.id = caixa.user
                        where users.id =$usuario_id ORDER BY caixa.id DESC LIMIT 1");
            $sql->execute();

            if($sql->rowCount() < 1)
                throw new \Exception("Erro na API");

            $vazamento = $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['vazamento'];

            $sql = $this->pdo->prepare(
                "SELECT rua FROM `rua` 
                        INNER JOIN users ON
                        users.id = rua.user
                        where users.id =$usuario_id ORDER BY rua.id DESC LIMIT 1");
            $sql->execute();

            if($sql->rowCount() < 1)
                throw new \Exception("Erro na API");

            $rua = $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['rua'];


            if($rua == 0){
                $info = [
                    "id"=> "O abastecimento externo está normal!",
                    "nome" => "Está faltando água da rua?",
                    "valor" => $rua
                ];
            }else{
                $info =   [
                    "id"=> "Interrupção do abastecimento externo",
                    "nome" => "Está faltando água da rua?",
                    "valor" => $rua
                ];
            }

            if($vazamento == 0){
                $info_vazamento = [
                    "id"=> "Não está havendo vazamento pelo ladrão!",
                    "nome" => "Está faltando água da rua?",
                    "valor" => $vazamento
                ];
            }else{
                $info_vazamento =   [
                    "id"=> "Está havendo vazamento pelo ladrão!",
                    "nome" => "Está faltando água da rua?",
                    "valor" => $vazamento
                ];
            }


            if($caixa === '20.00'){

                //nivel critico
                $info2 = [
                    "id"=> "Porcentagem de Água na Caixa: $caixa%
                    
ALERTA: NÍVEL CRÍTICO DE ÁGUA",
                    "nome" => "Porcentagem Água da Caixa",
                    "caixa" =>$caixa
                ];
            } else if($caixa === '0.00'){
                $info2 = [
                    "id"=> "Porcentagem de Água na Caixa: $caixa%
                    
ALERTA: RESERVATÓRIO VAZIO",
                    "nome" => "Porcentagem Água da Caixa",
                    "caixa" =>$caixa
                ];
            }
            else{
                $info2 = [
                    "id"=> "Porcentagem de Água na Caixa: $caixa%",
                    "nome" => "Porcentagem Água da Caixa",
                    "caixa" =>$caixa
                ];
            }


            $dados = [

                [
                  "id"=>"Olá, $usuario",
                  "nome"=>$usuario,

                ],

                [
                    "id"=>"Identificação da sua instalação: $instalacao",
                    "nome"=>$instalacao,

                ],

                $info2,

                $info,

                $info_vazamento




            ];

            return $dados;

        }



        public function refresh(){


            $caixa = $this->getCaixa();
            $rua = $this->getRua();
            $vazamento = $this->getVazamento();
            $instalacao = $this->getInstalacao();

            $sql = $this->pdo->prepare(
                "SELECT * FROM `users` WHERE id = :instalacao"
            );

            $sql->bindParam(":instalacao", $instalacao);
            $sql->execute();
            $inteiro = $sql->fetchAll(\PDO::FETCH_ASSOC);

            $id_usuario = $inteiro[0]['id'];
            $expo = $inteiro[0]['expo_token'];


             $sql = $this->pdo->prepare(
                 "INSERT INTO `rua` (`id`, `user`, `rua`, `created_at`) VALUES (NULL, :usuario, :rua, current_timestamp());"
             );

            $sql->bindParam(":usuario", $id_usuario);
            $sql->bindParam(":rua", $rua);

            $sql->execute();


            $sql = $this->pdo->prepare(
                "INSERT INTO `caixa` (`id`, `user`, `caixa`, `vazamento`, `created_at`) VALUES (NULL, :usuario, :caixa, :vazamento, current_timestamp());"
            );

            $sql->bindParam(":usuario", $id_usuario);
            $sql->bindParam(":vazamento", $vazamento);
            $sql->bindParam(":caixa", $caixa);

            $sql->execute();


            if($rua == 1) {

                return $this->send($caixa,$expo);


            }else{
                return "OK";
            }

        }


        public function cadastro(){

            $email = $this->getEmail();
            $password = $this->getPassword();
            $nome = $this->getNome();
            $senha = password_hash($password,PASSWORD_DEFAULT);


            $sql = $this->pdo->prepare(
                "INSERT INTO users (name,email,password) VALUES (:name,:email,:password)"
            );

            $sql->bindParam(":name", $nome);
            $sql->bindParam(":email", $email);
            $sql->bindParam(":password", $senha);

            $sql->execute();

            if($sql->rowCount() !== 1)
                throw new \Exception("Erro ao cadastrar usuário");
            else
                return "OK";


        }



        public function send($caixa,$expo){


            //para bater a notificacao
            // faz a alteração em update status - se houver vazamento chama essa função

            $caixa = number_format($caixa,2,'.','.');

            if($caixa == '20.00'){
                $info = "Foi identificado a interrupção do abastecimento externo! Economize água! O reservatório está em Nível Crítico: $caixa %";
            }

            else if($caixa == '0.00'){
                $info = "Foi identificado a interrupção do abastecimento externo! Economize água! O reservatório está Vazio: $caixa %";
            }

            else {
                $info ="Foi identificado interrupção do abastecimento externo! Economize água! A porcentagem de água da caixa é: $caixa %";
            }


            try{


                $postFields = json_encode([

                    "to" => $expo,
                    "title" => "Foi identificado interrupção do abastecimento externo!",
                    "body" => $info

                ]);


                $curl = curl_init();

                curl_setopt_array($curl, array(

                        CURLOPT_URL => 'https://exp.host/--/api/v2/push/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $postFields,
                        CURLOPT_HTTPHEADER => array(

                            "content-type: application/json"

                        ),
                    )
                );

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {

                    $this->response['message'] = "cURL Error #:" . $err;

                } else {

                    $this->response['message'] = $response;

                }

            } catch(\Exception $e){

                $this->response['message'] = $e->getMessage();

            }

            return $this->response;

        }






    }
