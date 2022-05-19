<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_materia".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property int $area_id Área
 * @property string $color Color
 * @property string $tipo
 * @property int $tipo_materia_id
 * @property string $peso
 * @property int $orden
 * @property int $promedia
 * @property string $nombre_mec
 *
 * @property ScholarisActividadIndagacionCurso[] $scholarisActividadIndagacionCursos
 * @property ScholarisClase[] $scholarisClases
 */
class ScholarisMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'area_id', 'tipo_materia_id', 'orden', 'promedia'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'area_id', 'tipo_materia_id', 'orden', 'promedia', 'curriculo_asignatura_id', 'curriculo_nivel_id'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name', 'area_id', 'color', 'abreviarura'], 'required'],
            [['tipo', 'last_name'], 'string'],
            [['abreviarura'], 'string'],
            [['peso'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['color'], 'string', 'max' => 10],
            [['language_code'], 'string', 'max' => 5],
            [['nombre_mec'], 'string', 'max' => 250],
            [['is_active'], 'boolean'],
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
            'area_id' => 'Area ID',
            'color' => 'Color',
            'tipo' => 'Tipo',
            'tipo_materia_id' => 'Tipo Materia ID',
            'peso' => 'Peso',
            'orden' => 'Orden',
            'promedia' => 'Promedia',
            'nombre_mec' => 'Nombre Mec',
            'abreviarura' => 'Abreviatura',
            'curriculo_asignatura_id' => 'Asignatura Currículo',
            'curriculo_nivel_id' => 'Nivel Currículo',
            'last_name' => 'Nombre Completo',
            'is_active' => 'Activo',
            'language_code' => 'Idioma'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getScholarisActividadIndagacionCursos()
    // {
    //     return $this->hasMany(ScholarisActividadIndagacionCurso::className(), ['materia_id' => 'id']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisClases()
    {
        return $this->hasMany(ScholarisClase::className(), ['idmateria' => 'id']);
    }
    
    
    public function getArea(){
        return $this->hasOne(ScholarisArea::className(),['id' => 'area_id'] );
    }

    public function getCurriculoNivel(){
        return $this->hasOne(CurriculoMecNiveles::className(),['id' => 'curriculo_nivel_id']);
    }
    
    public function getCurriculoAsignatura(){
        return $this->hasOne(CurriculoMecAsignatutas::className(),['id' => 'curriculo_asignatura_id']);
    }


}
