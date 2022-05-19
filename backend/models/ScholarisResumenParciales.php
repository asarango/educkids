<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_resumen_parciales".
 *
 * @property int $id
 * @property string $actualizacion_fecha
 * @property int $alumno_id
 * @property int $clase_id
 * @property int $bloque_id
 * @property string $calificacion
 *
 * @property OpStudent $alumno
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisClase $clase
 */
class ScholarisResumenParciales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_resumen_parciales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actualizacion_fecha', 'alumno_id', 'clase_id', 'bloque_id'], 'required'],
            [['actualizacion_fecha'], 'safe'],
            [['alumno_id', 'clase_id', 'bloque_id'], 'default', 'value' => null],
            [['alumno_id', 'clase_id', 'bloque_id'], 'integer'],
            [['calificacion'], 'number'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actualizacion_fecha' => 'Actualizacion Fecha',
            'alumno_id' => 'Alumno ID',
            'clase_id' => 'Clase ID',
            'bloque_id' => 'Bloque ID',
            'calificacion' => 'Calificacion',
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
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
