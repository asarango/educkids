<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pud".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $bloque_id
 * @property string $titulo
 * @property string $fecha_inicio
 * @property string $fecha_finalizacion
 * @property string $objetivo_unidad
 * @property string $ac_necesidad_atendida
 * @property string $ac_adaptacion_aplicada
 * @property int $ac_responsable_dece
 * @property string $bibliografia
 * @property string $observaciones
 * @property int $quien_revisa_id
 * @property int $quien_aprueba_id
 * @property string $estado
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpFaculty $quienRevisa
 * @property OpFaculty $quienAprueba
 */
class ScholarisPlanPud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'bloque_id', 'titulo', 'fecha_inicio', 'fecha_finalizacion', 'objetivo_unidad', 'ac_necesidad_atendida', 'ac_adaptacion_aplicada', 'ac_responsable_dece', 'bibliografia', 'observaciones', 'quien_revisa_id', 'quien_aprueba_id', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['clase_id', 'bloque_id', 'ac_responsable_dece', 'quien_revisa_id', 'quien_aprueba_id'], 'default', 'value' => null],
            [['clase_id', 'bloque_id', 'ac_responsable_dece', 
                'quien_revisa_id', 'quien_aprueba_id',
                'pud_original', 'total_semanas', 'total_periodos'], 
            'integer'],
            [['fecha_inicio', 'fecha_finalizacion', 'creado_fecha', 'actualizado_fecha'], 'safe'],
            [['objetivo_unidad', 'ac_necesidad_atendida', 'ac_adaptacion_aplicada', 'bibliografia', 'observaciones'], 'string'],
            [['titulo'], 'string', 'max' => 150],
            [['estado'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['quien_revisa_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['quien_revisa_id' => 'id']],
            [['quien_aprueba_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['quien_aprueba_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'bloque_id' => 'Bloque ID',
            'titulo' => 'Titulo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_finalizacion' => 'Fecha Finalizacion',
            'objetivo_unidad' => 'Objetivo Unidad',
            'ac_necesidad_atendida' => 'Ac Necesidad Atendida',
            'ac_adaptacion_aplicada' => 'Ac Adaptacion Aplicada',
            'ac_responsable_dece' => 'Ac Responsable Dece',
            'bibliografia' => 'Bibliografia',
            'observaciones' => 'Observaciones',
            'quien_revisa_id' => 'Quien Revisa ID',
            'quien_aprueba_id' => 'Quien Aprueba ID',
            'estado' => 'Estado',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
            'pud_original' => 'Pud Original Codigo',
            'total_semanas' => 'Total Semanas',
            'total_periodos' => 'Total Periodos'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuienRevisa()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'quien_revisa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuienAprueba()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'quien_aprueba_id']);
    }
    
    public function getBloque(){
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }
    
    public function getClase(){
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
    
}
