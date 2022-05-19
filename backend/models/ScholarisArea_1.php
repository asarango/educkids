<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_area".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $period_id Período Académico
 * @property int $idcategoriamateria
 * @property int $section_id
 * @property string $estado_codigo
 * @property int $promedia
 * @property string $codigo
 * @property int $ministeriable
 * @property string $nombre_mec
 * @property int $orden
 *
 * @property ScholarisAreaConfiguraciones[] $scholarisAreaConfiguraciones
 * @property ScholarisReporteNotasArea[] $scholarisReporteNotasAreas
 */
class ScholarisArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'idcategoriamateria', 'section_id', 'promedia', 'ministeriable', 'orden'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'idcategoriamateria', 'section_id', 'promedia', 'ministeriable', 'orden'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['period_id', 'codigo'], 'string', 'max' => 30],
            [['estado_codigo'], 'string', 'max' => 20],
            [['nombre_mec'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'period_id' => 'Period ID',
            'idcategoriamateria' => 'Idcategoriamateria',
            'section_id' => 'Section ID',
            'estado_codigo' => 'Estado Codigo',
            'promedia' => 'Promedia',
            'codigo' => 'Codigo',
            'ministeriable' => 'Ministeriable',
            'nombre_mec' => 'Nombre Mec',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAreaConfiguraciones()
    {
        return $this->hasMany(ScholarisAreaConfiguraciones::className(), ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteNotasAreas()
    {
        return $this->hasMany(ScholarisReporteNotasArea::className(), ['area_id' => 'id']);
    }
}
