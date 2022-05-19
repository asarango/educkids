<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_desagregacion_cabecera".
 *
 * @property int $id
 * @property int $year_from
 * @property int $year_to
 * @property bool $is_active
 * @property string $comments
 * @property int $scholaris_periodo_id
 * @property int $carga_horaria_semanal
 * @property int $semanas_trabajo
 * @property int $evaluacion_aprend_imprevistos
 * @property int $total_semanas_clase
 * @property int $total_periodos
 * @property string $estado
 * @property string $coordinador_user
 * @property string $fecha_envio_coordinador
 * @property string $fecha_revision_coordinacion
 * @property string $revision_coordinacion_observaciones
 * @property string $fecha_de_cambios
 * @property string $fecha_aprobacion_coordinacion
 * @property int $ism_area_materia_id
 *
 * @property PcaDetalle[] $pcaDetalles
 * @property PlanificacionBloquesUnidad[] $planificacionBloquesUnidads
 * @property IsmAreaMateria $ismAreaMateria
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class PlanificacionDesagregacionCabecera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_desagregacion_cabecera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year_from', 'year_to', 'scholaris_periodo_id', 'ism_area_materia_id'], 'required'],
            [['year_from', 'year_to', 'scholaris_periodo_id', 'carga_horaria_semanal', 'semanas_trabajo', 'evaluacion_aprend_imprevistos', 'total_semanas_clase', 'total_periodos', 'ism_area_materia_id'], 'default', 'value' => null],
            [['year_from', 'year_to', 'scholaris_periodo_id', 'carga_horaria_semanal', 'semanas_trabajo', 'evaluacion_aprend_imprevistos', 'total_semanas_clase', 'total_periodos', 'ism_area_materia_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['comments', 'estado', 'revision_coordinacion_observaciones'], 'string'],
            [['fecha_envio_coordinador', 'fecha_revision_coordinacion', 'fecha_de_cambios', 'fecha_aprobacion_coordinacion'], 'safe'],
            [['coordinador_user'], 'string', 'max' => 200],
            [['ism_area_materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmAreaMateria::className(), 'targetAttribute' => ['ism_area_materia_id' => 'id']],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year_from' => 'Year From',
            'year_to' => 'Year To',
            'is_active' => 'Is Active',
            'comments' => 'Comments',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
            'carga_horaria_semanal' => 'Carga Horaria Semanal',
            'semanas_trabajo' => 'Semanas Trabajo',
            'evaluacion_aprend_imprevistos' => 'Evaluacion Aprend Imprevistos',
            'total_semanas_clase' => 'Total Semanas Clase',
            'total_periodos' => 'Total Periodos',
            'estado' => 'Estado',
            'coordinador_user' => 'Coordinador User',
            'fecha_envio_coordinador' => 'Fecha Envio Coordinador',
            'fecha_revision_coordinacion' => 'Fecha Revision Coordinacion',
            'revision_coordinacion_observaciones' => 'Revision Coordinacion Observaciones',
            'fecha_de_cambios' => 'Fecha De Cambios',
            'fecha_aprobacion_coordinacion' => 'Fecha Aprobacion Coordinacion',
            'ism_area_materia_id' => 'Ism Area Materia ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPcaDetalles()
    {
        return $this->hasMany(PcaDetalle::className(), ['desagregacion_cabecera_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidads()
    {
        return $this->hasMany(PlanificacionBloquesUnidad::className(), ['plan_cabecera_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMateria()
    {
        return $this->hasOne(IsmAreaMateria::className(), ['id' => 'ism_area_materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }
}
