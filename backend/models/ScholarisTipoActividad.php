<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tipo_actividad".
 *
 * @property int $id
 * @property string $nombre_nacional Nombre
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property int $orden Orden
 * @property string $nombre_pai Nombre PAI
 * @property string $tipo
 *
 * @property ScholarisNotasAutomaticasParcial[] $scholarisNotasAutomaticasParcials
 */
class ScholarisTipoActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tipo_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_nacional'], 'required'],
            [['create_uid', 'write_uid', 'orden'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'orden'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['nombre_nacional', 'nombre_pai'], 'string', 'max' => 50],
            [['tipo'], 'string', 'max' => 1],
            [['activo'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_nacional' => 'Nombre Nacional',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'orden' => 'Orden',
            'nombre_pai' => 'Nombre Pai',
            'tipo' => 'Tipo',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisNotasAutomaticasParcials()
    {
        return $this->hasMany(ScholarisNotasAutomaticasParcial::className(), ['tipo_actividad_id' => 'id']);
    }
}
