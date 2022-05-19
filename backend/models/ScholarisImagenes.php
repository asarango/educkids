<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_imagenes".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre_archivo
 * @property int $alto_pixeles
 * @property int $ancho_pixeles
 * @property string $detalle
 * @property bool $imagen_educandi
 */
class ScholarisImagenes extends \yii\db\ActiveRecord
{
    
    public $fileImagen;
    
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_imagenes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'alto_pixeles', 'ancho_pixeles', 'detalle'], 'required'],
            [['fileImagen'], 'file', 'extensions' => 'jpg,png,jpeg'],
            [['alto_pixeles', 'ancho_pixeles'], 'default', 'value' => null],
            [['alto_pixeles', 'ancho_pixeles'], 'integer'],
            [['detalle'], 'string'],
            [['imagen_educandi'], 'boolean'],
            [['codigo', 'nombre_archivo'], 'string', 'max' => 50],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nombre_archivo' => 'Nombre Archivo',
            'alto_pixeles' => 'Alto Pixeles',
            'ancho_pixeles' => 'Ancho Pixeles',
            'detalle' => 'Detalle',
            'imagen_educandi' => 'Imagen Educandi',
            'fileImagen' => 'Inserta una imagen',
        ];
    }
}
