<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_intituto_datos_generales".
 *
 * @property int $id
 * @property int $instituto_id
 * @property string $direccion
 * @property string $codigo_amie
 * @property string $telefono
 * @property string $provincia
 * @property string $canton
 * @property string $parroquia
 * @property string $correo
 * @property string $sitio_web
 * @property string $sostenimiento
 * @property string $regimen
 * @property string $modalidad
 * @property string $niveles_curriculares
 * @property string $subniveles
 * @property string $distrito
 * @property string $circuito
 * @property string $jornada
 * @property string $horario_trabajo
 * @property string $local
 * @property string $genero
 * @property int $ejecucion_desde
 * @property int $ejecucion_hasta
 * @property string $financiamiento
 *
 * @property OpInstitute $instituto
 */
class ScholarisIntitutoDatosGenerales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_intituto_datos_generales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instituto_id', 'direccion', 'codigo_amie', 'telefono', 'provincia', 'canton', 'parroquia', 'correo', 'sostenimiento', 'regimen', 'modalidad', 'niveles_curriculares', 'subniveles', 'distrito', 'jornada', 'horario_trabajo', 'local', 'genero', 'ejecucion_desde', 'ejecucion_hasta', 'financiamiento'], 'required'],
            [['instituto_id', 'ejecucion_desde', 'ejecucion_hasta'], 'default', 'value' => null],
            [['instituto_id', 'ejecucion_desde', 'ejecucion_hasta'], 'integer'],
            [['direccion', 'correo', 'sitio_web', 'modalidad', 'jornada', 'horario_trabajo', 'genero'], 'string', 'max' => 150],
            [['codigo_amie', 'telefono', 'provincia', 'canton', 'parroquia', 'sostenimiento', 'regimen', 'distrito', 'circuito'], 'string', 'max' => 50],
            [['niveles_curriculares', 'subniveles'], 'string', 'max' => 250],
            [['local', 'financiamiento'], 'string', 'max' => 30],
            [['instituto_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['instituto_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instituto_id' => 'Instituto ID',
            'direccion' => 'Direccion',
            'codigo_amie' => 'Codigo Amie',
            'telefono' => 'Telefono',
            'provincia' => 'Provincia',
            'canton' => 'Canton',
            'parroquia' => 'Parroquia',
            'correo' => 'Correo',
            'sitio_web' => 'Sitio Web',
            'sostenimiento' => 'Sostenimiento',
            'regimen' => 'Regimen',
            'modalidad' => 'Modalidad',
            'niveles_curriculares' => 'Niveles Curriculares',
            'subniveles' => 'Subniveles',
            'distrito' => 'Distrito',
            'circuito' => 'Circuito',
            'jornada' => 'Jornada',
            'horario_trabajo' => 'Horario Trabajo',
            'local' => 'Local',
            'genero' => 'Genero',
            'ejecucion_desde' => 'Ejecucion Desde',
            'ejecucion_hasta' => 'Ejecucion Hasta',
            'financiamiento' => 'Financiamiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituto()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'instituto_id']);
    }
}
