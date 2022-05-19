<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificaciones_parcial_cambios".
 *
 * @property int $id
 * @property int $bloque_id
 * @property int $grupo_id
 * @property string $codigo_que_califica
 * @property string $nota_anterior
 * @property string $nota_nueva
 * @property string $motivo_cambio
 * @property string $fecha_cambio
 * @property string $creado_por
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisCalificacionesParcialCambios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificaciones_parcial_cambios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bloque_id', 'grupo_id', 'codigo_que_califica', 'nota_nueva', 'motivo_cambio', 'fecha_cambio', 'creado_por'], 'required'],
            [['bloque_id', 'grupo_id'], 'default', 'value' => null],
            [['bloque_id', 'grupo_id'], 'integer'],
            [['nota_anterior', 'nota_nueva'], 'number'],
            [['motivo_cambio'], 'string'],
            [['fecha_cambio'], 'safe'],
            [['codigo_que_califica'], 'string', 'max' => 30],
            [['creado_por'], 'string', 'max' => 200],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bloque_id' => 'Bloque ID',
            'grupo_id' => 'Grupo ID',
            'codigo_que_califica' => 'Codigo Que Califica',
            'nota_anterior' => 'Nota Anterior',
            'nota_nueva' => 'Nota Nueva',
            'motivo_cambio' => 'Motivo Cambio',
            'fecha_cambio' => 'Fecha Cambio',
            'creado_por' => 'Creado Por',
        ];
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
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
