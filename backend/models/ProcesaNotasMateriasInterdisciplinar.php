<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ProcesaNotasMateriasInterdisciplinar extends \yii\db\ActiveRecord {

    private $paralelo;
    private $claseComportamientoId;
    
    public function __construct($paralelo) {
        $this->paralelo = $paralelo;
        $this->claseComportamientoId = $this->busca_clase_comportamiento(); // busca el numero de clase para tomar en cuenta lo calificaco en interdisciplinar
        
        
        
        
    }
    
    private function busca_clase_comportamiento(){
        $con = \Yii::$app->db;
        $query = "select 	c.id 
                    from	scholaris_clase c
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                    where	c.paralelo_id = $this->paralelo
                                    and mm.tipo = 'COMPORTAMIENTO';";
    
        $res = $con->createCommand($query)->queryOne();
        return $res['id'];
    }
    

    

}
