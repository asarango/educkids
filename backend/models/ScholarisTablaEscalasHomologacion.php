<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tabla_escalas_homologacion".
 *
 * @property int $id
 * @property string $nombre
 * @property string $abreviatura
 * @property string $descripcion
 * @property string $valor_numerico
 * @property string $rango_minimo
 * @property string $rango_maximo
 * @property string $corresponde_a
 * @property string $scholaris_periodo
 * @property string $section_codigo
 */
class ScholarisTablaEscalasHomologacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tabla_escalas_homologacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['valor_numerico', 'rango_minimo', 'rango_maximo'], 'number'],
            [['nombre'], 'string', 'max' => 80],
            [['abreviatura'], 'string', 'max' => 10],
            [['descripcion'], 'string', 'max' => 255],
            [['corresponde_a'], 'string', 'max' => 50],
            [['scholaris_periodo', 'section_codigo'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'abreviatura' => 'Abreviatura',
            'descripcion' => 'Descripcion',
            'valor_numerico' => 'Valor Numerico',
            'rango_minimo' => 'Rango Minimo',
            'rango_maximo' => 'Rango Maximo',
            'corresponde_a' => 'Corresponde A',
            'scholaris_periodo' => 'Scholaris Periodo',
            'section_codigo' => 'Section Codigo',
        ];
    }
}
