<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_diploma".
 *
 * @property int $id
 * @property int $planificacion_bloque_unidad_id
 * @property string $objetivo_asignatura
 * @property string $concepto_clave
 * @property string $objetivo_evaluacion
 * @property string $intrumentos
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 * @property string $descripcion_texto_unidad
 * @property string $habilidades
 * @property string $proceso_aprendizaje
 * @property string $detalle_cas
 * @property string $detalle_len_y_aprendizaje
 * @property string $conexion_tdc
 * @property string $recurso
 * @property string $reflexion_funciono
 * @property string $reflexion_no_funciono
 * @property string $reflexion_observacion
 *
 * @property PlanificacionBloquesUnidad $planificacionBloqueUnidad
 * @property PlanificacionVerticalDiplomaHabilidades[] $planificacionVerticalDiplomaHabilidades
 * @property PlanificacionVerticalDiplomaRelacionTdc[] $planificacionVerticalDiplomaRelacionTdcs
 */
class PlanificacionVerticalDiploma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_diploma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloque_unidad_id', 'objetivo_asignatura', 'concepto_clave', 'objetivo_evaluacion', 'intrumentos', 'created', 'created_at'], 'required'],
            [['planificacion_bloque_unidad_id'], 'default', 'value' => null],
            [['planificacion_bloque_unidad_id'], 'integer'],
            [['objetivo_asignatura', 'concepto_clave', 'objetivo_evaluacion', 'intrumentos', 'descripcion_texto_unidad', 'habilidades', 'proceso_aprendizaje', 'detalle_cas', 'detalle_len_y_aprendizaje', 'conexion_tdc', 'recurso', 'reflexion_funciono', 'reflexion_no_funciono', 'reflexion_observacion'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['planificacion_bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['planificacion_bloque_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planificacion_bloque_unidad_id' => 'Planificacion Bloque Unidad ID',
            'objetivo_asignatura' => 'Objetivo Asignatura',
            'concepto_clave' => 'Concepto Clave',
            'objetivo_evaluacion' => 'Objetivo Evaluacion',
            'intrumentos' => 'Intrumentos',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
            'descripcion_texto_unidad' => 'Descripcion Texto Unidad',
            'habilidades' => 'Habilidades',
            'proceso_aprendizaje' => 'Proceso Aprendizaje',
            'detalle_cas' => 'Detalle Cas',
            'detalle_len_y_aprendizaje' => 'Detalle Len Y Aprendizaje',
            'conexion_tdc' => 'Conexion Tdc',
            'recurso' => 'Recurso',
            'reflexion_funciono' => 'Reflexion Funciono',
            'reflexion_no_funciono' => 'Reflexion No Funciono',
            'reflexion_observacion' => 'Reflexion Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloqueUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'planificacion_bloque_unidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalDiplomaHabilidades()
    {
        return $this->hasMany(PlanificacionVerticalDiplomaHabilidades::className(), ['vertical_diploma_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalDiplomaRelacionTdcs()
    {
        return $this->hasMany(PlanificacionVerticalDiplomaRelacionTdc::className(), ['vertical_diploma_id' => 'id']);
    }
    //Devuelve solo los check que estan activados, para presentar en el reporte.
    public function consultar_tdc_ckeck_reporte($planVertDiplId) 
    {
       //consulta los tdc que han sido marcados con check
       $con = Yii::$app->db;
       $query = "select p.opcion
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='RELACION_TDC'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id                
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    }
    //Devuelve solo los check que estan activados, para presentar en el reporte.
    public function consultar_habilidad_check_reporte($plaVertDiplId) 
    {    
        //consulta las habilidades que han sido marcados con check           
        $con = Yii::$app->db;        
        $query = "select h.es_exploracion 
                from contenido_pai_habilidades h, planificacion_vertical_diploma_habilidades ph,
                planificacion_vertical_diploma pvd 
                where pvd.id =$plaVertDiplId 
                and ph.vertical_diploma_id = pvd.id   
                and ph.habilidad_id  = h.id ";
        $resultado = $con->createCommand($query)->queryAll(); 
       
       return $resultado;       
    }
    
}
