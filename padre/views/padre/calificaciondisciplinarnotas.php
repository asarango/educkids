<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;
$modelLibreta = backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'publicalib'])->one();

$this->title = 'Educandi-Portal';
$hoy = date('Y-m-d H:i:s');
?>


<div class="padre-calificaciondisciplinarnotas">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['calificacionpadre', 'id' => $modelAlumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">CALIFICACIÓN MÉTODO DISCIPLINAR</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelBloque->name ?></li>
        </ol>
    </nav> 


    <div style="padding-left: 40px; padding-right: 40px">
        <div class="table table-responsive shadow-lg" style="font-size: 10px; padding: 30px">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <tr>
                    <td align="center"><strong>#</strong></td>
                    <td align="center"><strong>ASIGNATURA</strong></td>
                    <td align="center"><strong>NOTA</strong></td>
                </tr>

                <?php
                //echo date("d-m-Y",strtotime($fecha_actual."- 1 days")); 
                $fechaLimite = date("Y-m-d", strtotime($modelBloque->hasta . "- 5 days"));

                $i = 0;
                foreach ($modelNotas as $nota) {
                    $i++;
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td aling="left"><?= $nota['materia'] ?></td>
                        <td class="text-center">
                            <?php
                            if ($hoy >= $fechaLimite && $hoy <= $modelBloque->hasta) {
                                ?>



                                <select name="nota" id="nota<?= $modelBloque->id . $nota['grupo_id'] ?>" 
                                        onchange="cambiarNota(<?= $modelBloque->id ?>, <?= $nota['grupo_id'] ?>)"
                                        class="form-control">
                                    <option value="<?= $nota['nota'] ?>"><?= $nota['nota'] ?></option>
                                    <option value="1">1</option>
                                    <option value="0.75">0.75</option>
                                    <option value="0.50">0.50</option>
                                    <!--<option value="0.25">0.25</option>-->
                                    <option value="0.00">0.00</option>
                                </select>

                                <?php
                            } else {
                                echo $nota['nota'];
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

            </table>
        </div>
    </div>
</div>

<script>
    //document.getElementById("calificar").focus();

    function cambiarNota(bloqueId, grupoId) {
        var idx = '#nota' + bloqueId + grupoId;
        var nota = $(idx).val();



        var url = "<?= Url::to(['cambia-nota-ajax-padre']) ?>";

        $.post(
                url,
                {
                    nota: nota,
                    bloqueId: bloqueId,
                    grupoId: grupoId
                },
                function (result) {
                    //$("#res").html(result);
                }
        );

    }

    function NumCheck(e, field) {
        key = e.keyCode ? e.keyCode : e.which

        // backspace
        if (key == 8)
            return true

        // 0-9
        if (key > 47 && key < 58) {
            if (field.value == "")
                return true

            regexp = /.[0-9]{2}$/
            return !(regexp.test(field.value))
        }

        // .

        if (key == 46) {
            if (field.value == "")
                return false
            regexp = /^[0-9]+$/
            return regexp.test(field.value)
        }
        // other key

        if (key == 9)
            return true

        return false
    }
</script>

