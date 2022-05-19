<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_archivosprofesor".
 *
 * @property int $id
 * @property int $idactividad
 * @property string $archivo
 * @property string $fechasubido
 * @property string $nombre_archivo
 */
class ScholarisArchivosprofesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_archivosprofesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idactividad', 'nombre_archivo'], 'required'],
            [['idactividad'], 'default', 'value' => null],
            [['idactividad','orden'], 'integer'],
            [['fechasubido'], 'safe'],
            [['publicar'], 'boolean'],
            [['texto'],'string'],            
            [['archivo', 'nombre_archivo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idactividad' => 'Idactividad',
            'archivo' => 'Subir Archivos',
            'fechasubido' => 'Fechasubido',
            'nombre_archivo' => 'Título',
            'publicar' => 'Publicar',
            'texto' => 'Contenido',
            'orden' => 'Orden',
        ];
    }
}
