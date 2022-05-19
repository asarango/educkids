<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_clase_libreta".
 *
 * @property int $id
 * @property int $grupo_id
 * @property string $p1
 * @property string $p2
 * @property string $p3
 * @property string $pr1
 * @property string $pr180
 * @property string $ex1
 * @property string $ex120
 * @property string $q1
 * @property string $p4
 * @property string $p5
 * @property string $p6
 * @property string $pr2
 * @property string $pr280
 * @property string $ex2
 * @property string $ex220
 * @property string $q2
 * @property string $final_ano_normal
 * @property string $mejora_q1
 * @property string $mejora_q2
 * @property string $final_con_mejora
 * @property string $supletorio
 * @property string $remedial
 * @property string $gracia
 * @property string $final_total
 * @property string $estado
 *
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisClaseLibreta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_clase_libreta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id'], 'required'],
            [['grupo_id'], 'default', 'value' => null],
            [['grupo_id'], 'integer'],
            [['p1', 'p2', 'p3', 'pr1', 'pr180', 'ex1', 'ex120', 'q1', 'p4', 'p5', 'p6', 'pr2', 'pr280', 'ex2', 'ex220', 'q2', 'final_ano_normal', 'mejora_q1', 'mejora_q2', 'final_con_mejora', 'supletorio', 'remedial', 'gracia', 'final_total'], 'number'],
            [['estado'], 'string', 'max' => 50],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo_id' => 'Grupo ID',
            'p1' => 'P1',
            'p2' => 'P2',
            'p3' => 'P3',
            'pr1' => 'Pr1',
            'pr180' => 'Pr180',
            'ex1' => 'Ex1',
            'ex120' => 'Ex120',
            'q1' => 'Q1',
            'p4' => 'P4',
            'p5' => 'P5',
            'p6' => 'P6',
            'pr2' => 'Pr2',
            'pr280' => 'Pr280',
            'ex2' => 'Ex2',
            'ex220' => 'Ex220',
            'q2' => 'Q2',
            'final_ano_normal' => 'Final Ano Normal',
            'mejora_q1' => 'Mejora Q1',
            'mejora_q2' => 'Mejora Q2',
            'final_con_mejora' => 'Final Con Mejora',
            'supletorio' => 'Supletorio',
            'remedial' => 'Remedial',
            'gracia' => 'Gracia',
            'final_total' => 'Final Total',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
