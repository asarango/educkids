<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_unidad_micro".
 *
 * @property int $id
 * @property int $pca_id
 * @property int $orden
 * @property string $experiencia
 * @property string $fecha_inicia
 * @property string $fecha_termina
 * @property string $observaciones
 * @property string $estado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property KidsPca $pca
 */
class KidsUnidadMicro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_unidad_micro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pca_id', 'orden', 'experiencia', 'fecha_inicia', 'fecha_termina', 'estado', 'created_at', 'created'], 'required'],
            [['pca_id', 'orden'], 'default', 'value' => null],
            [['pca_id', 'orden'], 'integer'],
            [['experiencia', 'observaciones'], 'string'],
            [['fecha_inicia', 'fecha_termina', 'created_at', 'updated_at'], 'safe'],
            [['estado'], 'string', 'max' => 40],
            [['created', 'updated'], 'string', 'max' => 200],
            [['pca_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsPca::className(), 'targetAttribute' => ['pca_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pca_id' => 'Pca ID',
            'orden' => 'Orden',
            'experiencia' => 'Experiencia',
            'fecha_inicia' => 'Fecha Inicia',
            'fecha_termina' => 'Fecha Termina',
            'observaciones' => 'Observaciones',
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPca()
    {
        return $this->hasOne(KidsPca::className(), ['id' => 'pca_id']);
    }
}
