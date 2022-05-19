<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_rep_libreta".
 *
 * @property string $codigo
 * @property string $usuario
 * @property int $clase_id
 * @property int $promedia
 * @property int $tipo_uso_bloque
 * @property string $tipo
 * @property int $asignatura_id
 * @property string $asignatura
 * @property int $paralelo_id
 * @property int $alumno_id
 * @property int $area_id
 * @property string $p1
 * @property string $p2
 * @property string $p3
 * @property string $pr1
 * @property string $ex1
 * @property string $pr180
 * @property string $ex120
 * @property string $q1
 * @property string $p4
 * @property string $p5
 * @property string $p6
 * @property string $pr2
 * @property string $ex2
 * @property string $pr280
 * @property string $ex220
 * @property string $q2
 * @property string $nota_final
 *
 * @property OpStudent $alumno
 * @property ScholarisClase $clase
 */
class ScholarisRepLibreta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_rep_libreta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'usuario', 'clase_id', 'promedia', 'tipo_uso_bloque', 'tipo', 'asignatura_id', 'asignatura', 'paralelo_id', 'alumno_id'], 'required'],
            [['clase_id', 'promedia', 'tipo_uso_bloque', 'asignatura_id', 'paralelo_id', 'alumno_id', 'area_id'], 'default', 'value' => null],
            [['clase_id', 'promedia', 'tipo_uso_bloque', 'asignatura_id', 'paralelo_id', 'alumno_id', 'area_id'], 'integer'],
            [['p1', 'p2', 'p3', 'pr1', 'ex1', 'pr180', 'ex120', 'q1', 'p4', 'p5', 'p6', 'pr2', 'ex2', 'pr280', 'ex220', 'q2', 'nota_final', 'peso'], 'number'],
            [['codigo'], 'string', 'max' => 50],
            [['usuario', 'asignatura'], 'string', 'max' => 150],
            [['tipo','tipo_calificacion'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'usuario' => 'Usuario',
            'clase_id' => 'Clase ID',
            'promedia' => 'Promedia',
            'peso' => 'Peso',
            'tipo_uso_bloque' => 'Tipo Uso Bloque',
            'tipo' => 'Tipo',
            'asignatura_id' => 'Asignatura ID',
            'asignatura' => 'Asignatura',
            'paralelo_id' => 'Paralelo ID',
            'alumno_id' => 'Alumno ID',
            'area_id' => 'Area ID',
            'p1' => 'P1',
            'p2' => 'P2',
            'p3' => 'P3',
            'pr1' => 'Pr1',
            'ex1' => 'Ex1',
            'pr180' => 'Pr180',
            'ex120' => 'Ex120',
            'q1' => 'Q1',
            'p4' => 'P4',
            'p5' => 'P5',
            'p6' => 'P6',
            'pr2' => 'Pr2',
            'ex2' => 'Ex2',
            'pr280' => 'Pr280',
            'ex220' => 'Ex220',
            'q2' => 'Q2',
            'nota_final' => 'Nota Final',
            'tipo_calificacion' => 'Tipo Calificacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'alumno_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
