<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_actividad".
 *
 * @property int $id
 * @property string $name Nombre
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $quimestre Quimestre
 * @property string $tipo Tipo
 * @property string $desde Desde
 * @property string $hasta Hasta
 * @property int $orden Orden
 * @property string $scholaris_periodo_codigo
 * @property string $tipo_bloque
 * @property int $dias_laborados
 * @property string $estado
 * @property string $abreviatura
 * @property string $tipo_uso
 * @property string $bloque_inicia
 * @property string $bloque_finaliza
 *
 * @property ScholarisBloqueSemanas[] $scholarisBloqueSemanas
 * @property ScholarisNotasAutomaticasParcial[] $scholarisNotasAutomaticasParcials
 * @property ScholarisResumenParciales[] $scholarisResumenParciales
 */
class ScholarisBloqueActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','codigo_tipo_calificacion'], 'required'],
            [['create_uid', 'write_uid', 'orden', 'dias_laborados'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'orden', 'dias_laborados','instituto_id'], 'integer'],
            [['create_date', 'write_date', 'desde', 'hasta', 'bloque_inicia', 'bloque_finaliza'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['quimestre', 'tipo', 'scholaris_periodo_codigo', 'tipo_bloque'], 'string', 'max' => 20],
            [['estado', 'tipo_uso', 'codigo_tipo_calificacion'], 'string', 'max' => 30],
            [['abreviatura'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'quimestre' => 'Quimestre',
            'tipo' => 'Tipo',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'orden' => 'Orden',
            'scholaris_periodo_codigo' => 'Scholaris Periodo Codigo',
            'tipo_bloque' => 'Tipo Bloque',
            'dias_laborados' => 'Dias Laborados',
            'estado' => 'Estado',
            'abreviatura' => 'Abreviatura',
            'tipo_uso' => 'Tipo Uso',
            'bloque_inicia' => 'Bloque Inicia',
            'bloque_finaliza' => 'Bloque Finaliza',
            'instituto_id' => 'Instituto',
            'codigo_tipo_calificacion' => 'codigo_tipo_calificacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisBloqueSemanas()
    {
        return $this->hasMany(ScholarisBloqueSemanas::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisNotasAutomaticasParcials()
    {
        return $this->hasMany(ScholarisNotasAutomaticasParcial::className(), ['bloque_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenParciales()
    {
        return $this->hasMany(ScholarisResumenParciales::className(), ['bloque_id' => 'id']);
    }
    
    public function getInstituto(){
        return $this->hasOne(OpInstitute::className(),['id' => 'instituto_id']);
    }
    
    public function getCalificacion(){
        return $this->hasOne(ScholarisBloqueComoCalifica::className(), ['codigo' => 'codigo_tipo_calificacion']);
    }
}
