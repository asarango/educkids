<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttention */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$modelUser = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
$opFacultyId = faculty_id($usuario);
$dateNow = date("Y-m-d : H:i:s");
$departments = backend\models\OpDepartmentDece::find()->all();
$students = students();
$employeeId = empleado_id($usuario);
$externalDeriva = backend\models\OpExternalDerivation::find()->all();
$violenceModali = \backend\models\OpViolenceType::find()->all();
$violenceType = \backend\models\OpViolenceType::find()->all();
$violenceReason = \backend\models\OpViolenceReason::find()->all();
$attentionType = \backend\models\OpPsychologicalAttentionType::find()->all();
$specialNeeds = backend\models\OpSpecialNeeds::find()->all();
$substanceUse = backend\models\OpSubstanceUse::find()->all();
$faculties = faculties();
?>

<style>
    .sinBorde{
        /*        border: 0;
                background-color: black;
                font-size: 10px;*/
    }
</style>

<div class="op-psychological-attention-form" style="padding-left: 50px; padding-right: 50px;">



    <div class="row">
        <div class="col-lg-6 col-md-6" style="background-color:  #f0f8fa ; padding-right: 30px; padding-left: 30px">
            <hr>
            <h4><strong>Detalle de la atención</strong></h4>
            <?php $form = ActiveForm::begin(); ?>

            <!--INICIA OCULTOS-->
            

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'create_date')->hiddenInput(['value' => $dateNow])->label(false);
            } else {
                echo $form->field($model, 'create_date')->hiddenInput()->label(false);
            }
            ?>

            <?= $form->field($model, 'create_uid')->hiddenInput(['value' => $modelUser->id])->label(false) ?>
            <?= $form->field($model, 'employee_id')->hiddenInput(['value' => $employeeId])->label(false) ?>
            <?= $form->field($model, 'write_date')->hiddenInput(['value' => $dateNow])->label(false) ?>
            <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $modelUser->id])->label(false) ?>

            <!--FIN DE OCULTOS-->


            <div class="row">
                <div class="col-lg-6 col-md-6" style="padding-right: 40px;">
                    <div class="row"><?= $form->field($model, 'subject', ['options' => ['class' => 'sinBorde']])->textInput(['maxlength' => true])->label('ASUNTO:') ?></div>
                    <div class="row">
                        <?php
                        $studentsList = ArrayHelper::map($students, 'id', 'name');
                        echo $form->field($model, 'student_id')->widget(Select2::className(), [
                            'data' => $studentsList,
                            'options' => [
                                'placeholder' => 'Seleccione Estudiantes...'
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('ESTUDIANTE:')
                        ?>
                    </div>    
                    <div class="row">
                        <?=
                        $form->field($model, 'date')->widget(DatePicker::className(), [
                            'name' => 'fecha',
                            'value' => date('d-M-Y', strtotime('+2 days')),
                            'options' => ['placeholder' => 'Seleccione fecha...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ])->label('FECHA:')
                        ?>
                    </div>
                    <div class="row">
                        <?php
                        $departmentList = ArrayHelper::map($departments, 'id', 'name');
                        echo $form->field($model, 'departament_id')->widget(Select2::className(), [
                            'data' => $departmentList,
                            'options' => ['placeholder' => 'Seleccione Departamento...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('DEPARTAMENTO:')
                        ?>
                    </div>

                </div>
                <div class="col-lg-6 col-md-6" style="padding-left: 40px">
                    <div class="row">
                        <?php
                        $attentionTypeList = ArrayHelper::map($attentionType, 'id', 'name');
                        echo $form->field($model, 'attention_type_id')->widget(Select2::className(), [
                            'data' => $attentionTypeList,
                            'options' => ['placeholder' => 'Seleccione Tipo de Atención...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('TIPO DE ATENCIÓN:');
                        ?>
                    </div>
                    <div class="row">
                        <?php
                        $specialNeedList = ArrayHelper::map($specialNeeds, 'id', 'name');
                        echo $form->field($model, 'special_need_id')->widget(Select2::className(), [
                            'data' => $specialNeedList,
                            'options' => [
                                'placeholder' => 'Seleccione Necesidad Especial...',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('NECESIDAD ESPECIAL:')
                        ?>
                    </div>
                    <div class="row">
                        <?php
                        $externalDerivationList = ArrayHelper::map($externalDeriva, 'id', 'name');
                        echo $form->field($model, 'external_derivation_id')->widget(Select2::className(), [
                            'data' => $externalDerivationList,
                            'options' => ['placeholder' => 'Seleccione Derivación External...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('DERIVACIÓN EXTERNA:')
                        ?>
                    </div>

                    <div class="row">
                        <?php
                        $substanceUseList = ArrayHelper::map($substanceUse, 'id', 'name');
                        echo $form->field($model, 'substance_use_id')->widget(Select2::className(), [
                            'data' => $substanceUseList,
                            'options' => [
                                'placeholder' => 'Substancias ...',
                                'onchange' => 'busca_padres(this,"' . Url::to(['/cajas-select/padres']) . '")'
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('USO DE SUBSTANCIAS:')
                        ?>
                    </div>
                </div>
            </div>





            <div class="row">
                <div class="col-md-6 col-lg-6" style="padding-right: 40px;">
                    <h3>Persona atendida</h3>
                    <div class="row">
                        <?php 
                        $facultiesList = ArrayHelper::map($faculties, 'id', 'profesor');
                        echo $form->field($model, 'attended_faculty_id')->widget(Select2::className(),[
                             'data' => $facultiesList,
                            'options' => [
                                'placeholder' => 'Seleccione Docente Atendido...',
                                //'onchange' => 'busca_padres(this,"' . Url::to(['/cajas-select/padres']) . '")'
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('DOCENTE') ?>
                    </div>
                        
                        
                    <div class="row">
                        <?=
                        $form->field($model, 'attended_student_id')->widget(Select2::className(), [
                            'data' => $studentsList,
                            'options' => [
                                'placeholder' => 'Seleccione Estudiante Atendido...',
                                'onchange' => 'busca_padres(this,"' . Url::to(['/cajas-select/padres']) . '")'
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('ATENCIÓN A ESTUDIANTE:')
                        ?>
                    </div>
                    <div class="row">
                        <div id="padres"></div>

                        <?= $form->field($model, 'attended_parent_id')->hiddenInput()->label(false) ?>
                    </div>                        

                </div>


                <div class="col-lg-6 col-md-6">
                    <h3>Violencia</h3>
                    <div class="row">
                        <?php
                        $violenceTypeList = ArrayHelper::map($violenceType, 'id', 'name');
                        echo $form->field($model, 'violence_type_id')->widget(Select2::className(), [
                            'data' => $violenceTypeList,
                            'options' => ['placeholder' => 'Seleccione Tipo de Violencia...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('TIPO DE VIOLENCIA:')
                        ?>
                    </div>

                    <div class="row">
                        <?php
                        $violenceModaliList = ArrayHelper::map($violenceModali, 'id', 'name');
                        echo $form->field($model, 'violence_modality_id')->widget(Select2::className(), [
                            'data' => $violenceModaliList,
                            'options' => ['placeholder' => 'Seleccione Violencia...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('MODALIDAD:');
                        ?>
                    </div>
                    <div class="row">
                        <?php
                        $violenceReasonList = ArrayHelper::map($violenceReason, 'id', 'name');
                        echo $form->field($model, 'violence_reason_id')->widget(Select2::className(), [
                            'data' => $violenceReasonList,
                            'options' => ['placeholder' => 'Seleccione Razón de Violencia...'],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ])->label('MOTIVO:')
                        ?>
                    </div>
                </div>
            </div>

            <div class="row"><?= $form->field($model, 'detail')->textarea(['rows' => 6])->label('DETALLE:') ?></div>
            <div class="row"><?= $form->field($model, 'agreements')->textarea(['rows' => 6])->label('ACUERDOS:') ?></div> 

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'state')->hiddenInput(['value' => 'draft'])->label(false);
            } else {
                echo $form->field($model, 'state')->hiddenInput()->label(false);
            }
            ?>

            <?= $form->field($model, 'special_attention')->checkbox() ?>

            <?= $form->field($model, 'persona_lidera')->textInput(['placeholder' => 'Firma / Nombre']) ?>

            <?php //$form->field($model, 'course_id')->textInput()  ?>
            <?php //$form->field($model, 'parallel_id')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-6 col-md-6" style="background-color: white; padding-left: 30px">

            <hr>
            <?php
            if (!$model->isNewRecord) {
                ?>

                <?php
                echo $this->render('/op-psychological-attention-asistentes/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'attentionId' => $model->id
                ]);
                
                echo '<hr>';
                echo Html::a('Validar', ['validate-attention', 'id' => $model->id],['class' => 'btn btn-danger']);
                
            }
            ?>


        </div>
    </div>







</div>



<script>
    function busca_padres(obj, url) {

        var parametros = {
            "id": $(obj).val()
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'post',
            beforeSend: function () {

            },
            success: function (response) {
                $('#padres').html(response);
            }
        });

    }

    function copiapadre(obj) {
        var padre = $(obj).val();
        $('#oppsychologicalattention-attended_parent_id').val(padre);
    }
</script>


<?php
/* * **
 * TOMA EL EMPLEADO_ID
 */

function empleado_id($usuario) {
    $con = Yii::$app->db;
    $query = "select 	emp.id 
                from	hr_employee emp
                                inner join resource_resource rr on rr.id = emp.resource_id 
                                inner join res_users u on u.id = rr.user_id 
                where	u.login  = '$usuario';";
    $res = $con->createCommand($query)->queryOne();
    return $res['id'];
}

/* * **
 * PARA EL FACULTY_ID
 */

function faculty_id($usuario) {
    $con = Yii::$app->db;
    $query = "select 	f.id 
                    from	op_faculty f
                                    inner join res_partner p on p.id = f.partner_id 
                                    inner join res_users u on u.partner_id = p.id 
                    where 	u.login = '$usuario';";
    $res = $con->createCommand($query)->queryOne();
    return $res['id'];
}

/* * *
 * PARA TOMAR STUDIANTES
 */

function students() {
    $instituteId = Yii::$app->user->identity->instituto_defecto;
    $con = Yii::$app->db;
    $query = "select 	id, concat(last_name, ' ', first_name, ' ', middle_name) as name 
                from 	op_student
                where	x_institute = $instituteId
                order by last_name, first_name, middle_name ;";
//    echo $query;
//    die();
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

/* * *
 * PARA TOMAR PROFESORE
 */

function faculties() {
    $con = Yii::$app->db;
    $query = "select id, concat(last_name, ' ', x_first_name) as profesor  from op_faculty order by last_name, x_first_name ;";
//    echo $query;
//    die();
    $res = $con->createCommand($query)->queryAll();
    return $res;
}
?>