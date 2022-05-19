<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class EnviarCorreo extends \yii\db\ActiveRecord {

    public function enviar($desdeArreglo, $aArreglo, $asunto, $cuerpoHtml){
        
        if(Yii::$app->mailer->compose()
                ->setFrom($desdeArreglo)
                ->setTo($aArreglo)
                ->setSubject($asunto)
//                ->setTextBody($cuerpo)
                ->setHtmlBody($cuerpoHtml)
                ->send()){
        
            echo 'correcto';
            
        }else{
            echo 'fallo';
        }
        
    }
    

}
