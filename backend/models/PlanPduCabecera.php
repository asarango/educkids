<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_pdu_cabecera".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $asignatura_curriculo_id
 * @property int $bloque_id
 * @property int $periodos
 * @property int $coordinador_id
 * @property int $vicerrector_id
 * @property string $planificacion_titulo
 * @property int $objetivo_por_nivel_id
 * @property string $estado
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property PlanArea $asignaturaCurriculo
 * @property PlanCurriculoObjetivos $objetivoPorNivel
 * @property ResPartner $coordinador
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisClase $clase
 * @property ScholarisInstitutoAutoridades $vicerrector
 */
class PlanPduCabecera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pdu_cabecera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'asignatura_curriculo_id', 'bloque_id', 'periodos', 'coordinador_id', 'vicerrector_id', 'planificacion_titulo', 'objetivo_por_nivel_id', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['clase_id', 'asignatura_curriculo_id', 'bloque_id', 'periodos', 'coordinador_id', 'vicerrector_id', 'objetivo_por_nivel_id'], 'default', 'value' => null],
            [['clase_id', 'asignatura_curriculo_id', 'bloque_id', 'periodos', 'coordinador_id', 'vicerrector_id', 'objetivo_por_nivel_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['planificacion_titulo'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['asignatura_curriculo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanArea::className(), 'targetAttribute' => ['asignatura_curriculo_id' => 'id']],
            [['objetivo_por_nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoObjetivos::className(), 'targetAttribute' => ['objetivo_por_nivel_id' => 'id']],
            [['coordinador_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['coordinador_id' => 'id']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['vicerrector_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisInstitutoAutoridades::className(), 'targetAttribute' => ['vicerrector_id' => 'id']],
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
            'asignatura_curriculo_id' => 'Asignatura Curriculo ID',
            'bloque_id' => 'Bloque ID',
            'periodos' => 'Periodos',
            'coordinador_id' => 'Coordinador ID',
            'vicerrector_id' => 'Vicerrector ID',
            'planificacion_titulo' => 'Planificacion Titulo',
            'objetivo_por_nivel_id' => 'Objetivo Por Nivel ID',
            'estado' => 'Estado',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCurriculo()
    {
        return $this->hasOne(PlanArea::className(), ['id' => 'asignatura_curriculo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoPorNivel()
    {
        return $this->hasOne(PlanCurriculoObjetivos::className(), ['id' => 'objetivo_por_nivel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoordinador()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'coordinador_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVicerrector()
    {
        return $this->hasOne(ScholarisInstitutoAutoridades::className(), ['id' => 'vicerrector_id']);
    }
}
