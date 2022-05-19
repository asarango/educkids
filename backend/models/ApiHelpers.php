<?php

namespace backend\models;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ApiHelpers extends \yii\db\ActiveRecord {

    private $secretKey; 
    
    public function validateTokenAuthorization($token){
        $str = 'f1c4fb04cc42a528ead96235c00a0cc94a24d29c7c93a854b21160c7ad11d7139ffa87d0ae745fbb843eeaa413275bd3d9caa9572c7df34d2a70336d1142781f';
        
        if($token == $str){
            //si es correcto el token
            $res = true;
        }else{
            //el token no es correcto
            $res = false;
        }
        
        return $res;
        
    }
    
}
