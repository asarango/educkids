<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPerido = backend\models\ScholarisPeriodo::findOne($periodoId);

$modelBloque = backend\models\ScholarisBloqueActividad::find()
        ->where(['scholaris_periodo_codigo' => $modelPerido->codigo, 'tipo_uso' => $modelClase->tipo_usu_bloque])
        ->orderBy('orden')
        ->all();

$this->title = 'PUD: ' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
;
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['profesor-inicio/clases']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-index">
    
<!--    <h3>Lista de mis planificaciones inicial</h3>-->
    <h1>
        <?php 
            echo $quimestre;
            $modelPlan = \backend\models\ScholarisPlanInicial::find()
                            ->where([
                                'clase_id' => $modelClase->id,
                                'quimestre_codigo' => $quimestre
                                ])->all();
            if(count($modelPlan)>0){
                
            }else{
                echo Html::a('COPIA PLANIFICACION', ['copiar', 
                                                'clase' => $modelClase->id,
                                                'quimestre' => $quimestre
                                               ], 
                                               //['class' => 'glyphicon glyphicon-pencil']);
                                               ['class' => 'btn btn-primary']);
            }
        ?>
    </h1>
    
    <?= Html::a('Material de apoyo', ['material','id' => $modelClase->id, 'quimestre' => $quimestre]); ?>
    
    <hr>
    
    <div class="row">
        <div class="col-md-3">
             <?php 
                    echo Html::a('QUIMESTRE I', ['index1','id' => $modelClase->id, 
                                'quimestre' => 'QUIMESTRE I']);
             ?>
        </div>
        
        <div class="col-md-3">
             <?php echo Html::a('QUIMESTRE II', ['index1','id' => $modelClase->id, 
                                'quimestre' => 'QUIMESTRE II']); ?>
        </div>
        
        <div class="col-md-3">
            
        </div>
        
    </div>
    
    <div class="row">
        <div class="col-md-5">
            <?php echo ejes($modelEjes, $quimestre, $modelClase->id) ?>
        </div>
        
        <div class="col-md-7">
            <?php echo planificacion($modelPlan); ?>
        </div>
    </div>
    
</div>

<?php
    function ejes($modelEjes, $quimestre, $clase){
//        $sentencias = new \backend\models\SentenciasPlanInicial();
        $html = '';
        foreach ($modelEjes as $eje){
            if($eje['color']=='#168FF7'){
                $color = 'primary';
            }elseif($eje['color']=='#FD9D03'){
                $color = 'warning';
            }elseif($eje['color']=='#037121'){
                $color = 'success';
            }else{
                $color = 'default';
            }
            $html.='<div class="panel panel-'.$color.'">';
            $html.='<div class="panel-heading">'.$eje['nombre'].'</div>';
            $html.='<div class="panel-body">';
            
            $ambitos = backend\models\CurCurriculoAmbito::find()
                    ->where(['eje_id' => $eje['id']])
                    ->all();
            $html.= '<ul>';
            foreach ($ambitos as $ambi){
                
                $html.='<li>';
                
                $html.= Html::a($ambi->codigo.' '.$ambi->nombre, ['planificar', 
                                                'id' => $ambi->id,
                                                'quimestre' => $quimestre,
                                                'clase' => $clase
                                               ], 
                                               //['class' => 'glyphicon glyphicon-pencil']);
                                               ['class' => 'btn btn-link']);
                $html.= '</li>';
            }
            
            $html.= '</ul>';
            
            $html.='</div>';
            $html.='</div>';
        }
        
        return $html;
    }
    
    function planificacion($modelPlan){
        $html = '';
        $html.= '<div class="table table-responsive">';
        $html.= '<table class="table table-striped">';
        $html.= '<tr>';
        $html.= '<td>CODIGO</td>';
        $html.= '<td>DESTREZA ORIGINAL</td>';
        $html.= '<td>DESTREZA DESAGREGADA</td>';
        $html.= '<td>ORDEN</td>';
        $html.= '</tr>';
        
//        echo '<pre>';
//        print_r($modelPlan);
//        die();
        
        foreach ($modelPlan as $plan){
//            echo '<pre>';
//            print_r($plan->codigo_destreza);
//            die();
            
            $modelDestreza = backend\models\CurCurriculoDestreza::find()
                    ->where(['codigo'=> $plan->codigo_destreza])
                    ->one();
            
//            echo '<pre>';
//            print_r($modelDestreza);
//            die();
            
            
            if($modelDestreza->ambito->eje->color=='#168FF7'){
                $color = 'primary';
            }elseif($modelDestreza->ambito->eje->color=='#FD9D03'){
                $color = 'warning';
            }else{
                $color = 'success';
            }
            $html.= '<tr>';
            $html.= '<td class="text text-'.$color.'">'.$plan->codigo_destreza.'</td>';
            $html.= '<td class="text text-'.$color.'">'.$plan->destreza_original.'</td>';
            $html.= '<td class="text text-'.$color.'">'.$plan->destreza_desagregada.'</td>';
            $html.= '<td class="text text-'.$color.'">';
            $html.= '<input type="number" value="'.$plan->orden.'" onchange="cambiaorden(value,'.$plan->id.');">';
            $html.= '</td>';
            $html.= '</tr>';
            
        }
        $html.= '</table>';
        $html.= '</div>';
        return $html;
    }

?>

<script>
    function cambiaorden(valor,id){
        var url = "<?= Url::to(['orden']) ?>";
        var parametros = {
            "orden": valor,
            "id": id
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
               // $("#bloque").html(response);
            }
        });   
    }
</script>
