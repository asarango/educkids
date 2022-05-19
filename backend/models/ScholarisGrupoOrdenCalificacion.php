<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_grupo_orden_calificacion".
 *
 * @property int $id
 * @property int $codigo_tipo_actividad
 * @property string $codigo_nombre_pai
 * @property int $grupo_numero
 * @property string $nombre_grupo
 */
class ScholarisGrupoOrdenCalificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_grupo_orden_calificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_tipo_actividad', 'codigo_nombre_pai', 'grupo_numero'], 'required'],
            [['codigo_tipo_actividad', 'grupo_numero'], 'default', 'value' => null],
            [['codigo_tipo_actividad', 'grupo_numero'], 'integer'],
            [['codigo_nombre_pai'], 'string', 'max' => 30],
            [['nombre_grupo'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo_tipo_actividad' => 'Codigo Tipo Actividad',
            'codigo_nombre_pai' => 'Codigo Nombre Pai',
            'grupo_numero' => 'Grupo Numero',
            'nombre_grupo' => 'Nombre Grupo',
        ];
    }
}
