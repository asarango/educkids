<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones de profesor';
//$this->params['breadcrumbs'][] = $this->title;


//$link = 'http://localhost/graficos/ejemplo1.php?cuadro=a:4:{i:0;a:6:{s:2:"id";i:1;s:11:"abreviatura";s:1:".";s:11:"descripcion";s:34:"Domina%20los%20aprendizajes%20requeridos";s:12:"rango_minimo";s:4:"9.00";s:12:"rango_maximo";s:5:"10.00";s:5:"total";i:17;}i:1;a:6:{s:2:"id";i:2;s:11:"abreviatura";s:1:".";s:11:"descripcion";s:35:"Alcanza%20los%20aprendizajes%20requeridos";s:12:"rango_minimo";s:4:"7.00";s:12:"rango_maximo";s:4:"8.99";s:5:"total";i:6;}i:2;a:6:{s:2:"id";i:3;s:11:"abreviatura";s:1:".";s:11:"descripcion";s:52:"Está%20proximo%20a%20alcanzar%20los%20aprendizajes%20requeridos";s:12:"rango_minimo";s:4:"4.01";s:12:"rango_maximo";s:4:"6.99";s:5:"total";i:0;}i:3;a:6:{s:2:"id";i:4;s:11:"abreviatura";s:1:".";s:11:"descripcion";s:38:"No%20alcanza%20los%20aprendizajes%20requeridos";s:12:"rango_minimo";s:4:"0.00";s:12:"rango_maximo";s:4:"4.00";s:5:"total";i:5;}}';

$datos=array(6,5,8,6);
//$labels =array(6,5,8,6);
$labels=array('pepe','juanita','Maria','Luis');
//$labels=array(1,2,3,4);

$d = serialize($datos);
$l = urlencode(serialize($labels));


//print_r($l);
//die();

$link = 'http://localhost/graficos/ejemplo1.php?datos='.$d.'&label='.$l;

//require_once ('lib/jpgraph/src/jpgraph.php');
//require_once ('http://localhost/graficos/lib/jpgraph/src/jpgraph.php');
//require_once ('/var/www/html/graficos/lib/jpgraph/src/jpgraph.php');
//require_once ('/var/www/html/graficos/lib/jpgraph/src/jpgraph_bar.php');
//
//$datos=array(6,5,8,6);
//$labels=array("pepe","juanita","Maria","Luis");
//
//
//
//$grafico = new Graph(500, 400, 'auto');
//$grafico->SetScale("textlin");
//$grafico->title->Set("Ejemplo de Grafica");
//$grafico->xaxis->title->Set("trabajadores");
//$grafico->xaxis->SetTickLabels($labels);
//$grafico->yaxis->title->Set("Horas Trabajadas");
//
//$barplot1 =new BarPlot($datos);
//$barplot1->SetWidth(30); // 30 pixeles de ancho para cada barra
//
//$grafico->Add($barplot1);
//$grafico->Stroke();

?>

<iframe src="<?= $link ?>" width="600" height="500"></iframe>