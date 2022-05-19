<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_refuerzo".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $bloque_id
 * @property int $orden_calificacion
 * @property string $promedio_normal
 * @property string $nota_refuerzo
 * @property string $nota_final
 * @property string $observacion
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisRefuerzo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_refuerzo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id', 'bloque_id', 'orden_calificacion', 'promedio_normal', 'nota_refuerzo', 'nota_final'], 'required'],
            [['grupo_id', 'bloque_id', 'orden_calificacion'], 'default', 'value' => null],
            [['grupo_id', 'bloque_id', 'orden_calificacion'], 'integer'],
            [['promedio_normal', 'nota_refuerzo', 'nota_final'], 'number'],
            [['observacion'], 'string'],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
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
            'bloque_id' => 'Bloque ID',
            'orden_calificacion' => 'Orden Calificacion',
            'promedio_normal' => 'Promedio Normal',
            'nota_refuerzo' => 'Nota Refuerzo',
            'nota_final' => 'Nota Final',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
