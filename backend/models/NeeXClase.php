<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "nee_x_clase".
 *
 * @property int $id
 * @property int $nee_id
 * @property int $clase_id
 * @property int $grado_nee
 * @property string $fecha_inicia
 * @property string $diagnostico_inicia
 * @property string $fecha_finaliza
 * @property string $diagnostico_finaliza
 *
 * @property Nee $nee
 * @property ScholarisClase $clase
 */
class NeeXClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nee_x_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nee_id', 'clase_id', 'grado_nee', 'fecha_inicia', 'diagnostico_inicia'], 'required'],
            [['nee_id', 'clase_id', 'grado_nee'], 'default', 'value' => null],
            [['nee_id', 'clase_id', 'grado_nee'], 'integer'],
            [['fecha_inicia', 'fecha_finaliza'], 'safe'],
            [['diagnostico_inicia', 'diagnostico_finaliza'], 'string'],
            [['nee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nee::className(), 'targetAttribute' => ['nee_id' => 'id']],
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
            'nee_id' => 'Nee ID',
            'clase_id' => 'Clase ID',
            'grado_nee' => 'Grado Nee',
            'fecha_inicia' => 'Fecha Inicia',
            'diagnostico_inicia' => 'Diagnostico Inicia',
            'fecha_finaliza' => 'Fecha Finaliza',
            'diagnostico_finaliza' => 'Diagnostico Finaliza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNee()
    {
        return $this->hasOne(Nee::className(), ['id' => 'nee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
