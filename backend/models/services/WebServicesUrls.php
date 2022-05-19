<?php
namespace backend\models\services;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class WebServicesUrls extends ActiveRecord{

    public $url;
    public $secretKey;

    public function __construct($service){

        if($service == 'odoo'){
            $this->url = 'http://192.168.20.25/web-service/public/index.php/api';
            $this->secretKey = 'esmiaplicacionmaestra';
        }elseif($service == 'academico'){
            $this->url = 'http://192.168.20.25/ws-academico/public/index.php/api';
            $this->secretKey = 'esmiaplicacionmaestra';
        }else{
            return array(
                'status' => 'false',
                'message' => 'No està proveido el servicio para '.$service
            );
        }

    }


    public function consumir_servicio($urlNew){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlNew);

        $headers = array();
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Auth: '.$this->secretKey;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

}
