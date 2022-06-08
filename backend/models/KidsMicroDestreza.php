<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_micro_destreza".
 *
 * @property int $id
 * @property int $micro_id
 * @property int $destreza_id
 * @property string $actividades_aprendizaje
 * @property string $recursos
 * @property string $indicadores_evaluacion
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property CurCurriculoDestreza $destreza
 * @property KidsUnidadMicro $micro
 */
class KidsMicroDestreza extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_micro_destreza';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['micro_id', 'destreza_id', 'actividades_aprendizaje', 'recursos', 'indicadores_evaluacion', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['micro_id', 'destreza_id'], 'default', 'value' => null],
            [['micro_id', 'destreza_id'], 'integer'],
            [['actividades_aprendizaje', 'recursos', 'indicadores_evaluacion'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurCurriculoDestreza::className(), 'targetAttribute' => ['destreza_id' => 'id']],
            [['micro_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsUnidadMicro::className(), 'targetAttribute' => ['micro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'micro_id' => 'Micro ID',
            'destreza_id' => 'Destreza ID',
            'actividades_aprendizaje' => 'Actividades Aprendizaje',
            'recursos' => 'Recursos',
            'indicadores_evaluacion' => 'Indicadores Evaluacion',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestreza()
    {
        return $this->hasOne(CurCurriculoDestreza::className(), ['id' => 'destreza_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMicro()
    {
        return $this->hasOne(KidsUnidadMicro::className(), ['id' => 'micro_id']);
    }
}
