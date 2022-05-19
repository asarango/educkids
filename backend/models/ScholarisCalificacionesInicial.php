<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificaciones_inicial".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $quimestre_id
 * @property int $plan_id
 * @property string $calificacion
 * @property string $observacion
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property ScholarisGrupoAlumnoClase $grupo
 * @property ScholarisPlanInicial $plan
 * @property ScholarisQuimestre $quimestre
 */
class ScholarisCalificacionesInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificaciones_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id', 'quimestre_id', 'plan_id', 'calificacion', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['grupo_id', 'quimestre_id', 'plan_id'], 'default', 'value' => null],
            [['grupo_id', 'quimestre_id', 'plan_id'], 'integer'],
            [['observacion'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['calificacion'], 'string', 'max' => 2],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanInicial::className(), 'targetAttribute' => ['plan_id' => 'id']],
            [['quimestre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisQuimestre::className(), 'targetAttribute' => ['quimestre_id' => 'id']],
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
            'quimestre_id' => 'Quimestre ID',
            'plan_id' => 'Plan ID',
            'calificacion' => 'Calificacion',
            'observacion' => 'Observacion',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(ScholarisPlanInicial::className(), ['id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuimestre()
    {
        return $this->hasOne(ScholarisQuimestre::className(), ['id' => 'quimestre_id']);
    }
}
