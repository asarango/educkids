<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "nee_detalle".
 *
 * @property int $id
 * @property int $nee_id
 * @property string $opcion_codigo
 * @property string $categoria
 * @property string $contenido
 * @property bool $es_seleccionado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property Nee $nee
 */
class NeeDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nee_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nee_id', 'created_at', 'created'], 'required'],
            [['nee_id'], 'default', 'value' => null],
            [['nee_id'], 'integer'],
            [['categoria', 'contenido'], 'string'],
            [['es_seleccionado'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['opcion_codigo'], 'string', 'max' => 40],
            [['created', 'updated'], 'string', 'max' => 200],
            [['nee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nee::className(), 'targetAttribute' => ['nee_id' => 'id']],
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
            'opcion_codigo' => 'Opcion Codigo',
            'categoria' => 'Categoria',
            'contenido' => 'Contenido',
            'es_seleccionado' => 'Es Seleccionado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNee()
    {
        return $this->hasOne(Nee::className(), ['id' => 'nee_id']);
    }
}
