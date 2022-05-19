<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad_recursos".
 *
 * @property int $id
 * @property int $actividad_id
 * @property string $tipo_codigo
 * @property string $nombre
 *
 * @property ScholarisActividad $actividad
 */
class ScholarisActividadRecursos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad_recursos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'tipo_codigo', 'nombre'], 'required'],
            [['actividad_id'], 'default', 'value' => null],
            [['actividad_id'], 'integer'],
            [['nombre'], 'string'],
            [['tipo_codigo'], 'string', 'max' => 30],
            [['actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisActividad::className(), 'targetAttribute' => ['actividad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actividad_id' => 'Actividad ID',
            'tipo_codigo' => 'Tipo Codigo',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActividad()
    {
        return $this->hasOne(ScholarisActividad::className(), ['id' => 'actividad_id']);
    }
}
