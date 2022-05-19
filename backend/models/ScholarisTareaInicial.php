<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tarea_inicial".
 *
 * @property int $id
 * @property int $clase_id
 * @property string $quimestre_codigo
 * @property string $titulo
 * @property string $fecha_inicio
 * @property string $fecha_entrega
 * @property string $nombre_archivo
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property ScholarisClase $clase
 */
class ScholarisTareaInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tarea_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'quimestre_codigo', 'titulo', 'fecha_inicio', 'fecha_entrega', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['clase_id'], 'default', 'value' => null],
            [['clase_id'], 'integer'],
            [['fecha_inicio', 'fecha_entrega', 'creado_fecha', 'actualizado_fecha'], 'safe'],
            [['quimestre_codigo'], 'string', 'max' => 30],
            [['titulo'], 'string', 'max' => 150],
            [['nombre_archivo'], 'string', 'max' => 50],
            [['tipo_material','link_videoconferencia','respaldo_videoconferencia'], 'string'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 200],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
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
            'quimestre_codigo' => 'Quimestre Codigo',
            'titulo' => 'Titulo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_entrega' => 'Fecha Entrega',
            'nombre_archivo' => 'Nombre Archivo',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
