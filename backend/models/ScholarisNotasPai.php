<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_notas_pai".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $alumno_id
 * @property string $alumno
 * @property string $quimestre
 * @property string $scholaris_periodo_codigo
 * @property string $sumativa1_a
 * @property string $sumativa2_a
 * @property string $sumativa3_a
 * @property string $nota_a
 * @property string $sumativa1_b
 * @property string $sumativa2_b
 * @property string $sumativa3_b
 * @property string $nota_b
 * @property string $sumativa1_c
 * @property string $sumativa2_c
 * @property string $sumativa3_c
 * @property string $nota_c
 * @property string $sumativa1_d
 * @property string $sumativa2_d
 * @property string $sumativa3_d
 * @property string $nota_d
 * @property string $suma_total
 * @property string $final_homologado
 * @property string $creado
 * @property int $usuario_crea
 * @property string $actualizado
 * @property int $usuario_modifica
 */
class ScholarisNotasPai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_notas_pai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'alumno_id', 'alumno', 'quimestre', 'scholaris_periodo_codigo', 'creado', 'usuario_crea', 'actualizado'], 'required'],
            [['clase_id', 'alumno_id', 'usuario_crea', 'usuario_modifica'], 'default', 'value' => null],
            [['clase_id', 'alumno_id', 'usuario_crea', 'usuario_modifica'], 'integer'],
            [['sumativa1_a', 'sumativa2_a', 'sumativa3_a', 'nota_a', 'sumativa1_b', 'sumativa2_b', 'sumativa3_b', 'nota_b', 'sumativa1_c', 'sumativa2_c', 'sumativa3_c', 'nota_c', 'sumativa1_d', 'sumativa2_d', 'sumativa3_d', 'nota_d', 'suma_total', 'final_homologado'], 'number'],
            [['creado', 'actualizado'], 'safe'],
            [['alumno'], 'string', 'max' => 150],
            [['quimestre', 'scholaris_periodo_codigo'], 'string', 'max' => 20],
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
            'alumno_id' => 'Alumno ID',
            'alumno' => 'Alumno',
            'quimestre' => 'Quimestre',
            'scholaris_periodo_codigo' => 'Scholaris Periodo Codigo',
            'sumativa1_a' => 'Sumativa1 A',
            'sumativa2_a' => 'Sumativa2 A',
            'sumativa3_a' => 'Sumativa3 A',
            'nota_a' => 'Nota A',
            'sumativa1_b' => 'Sumativa1 B',
            'sumativa2_b' => 'Sumativa2 B',
            'sumativa3_b' => 'Sumativa3 B',
            'nota_b' => 'Nota B',
            'sumativa1_c' => 'Sumativa1 C',
            'sumativa2_c' => 'Sumativa2 C',
            'sumativa3_c' => 'Sumativa3 C',
            'nota_c' => 'Nota C',
            'sumativa1_d' => 'Sumativa1 D',
            'sumativa2_d' => 'Sumativa2 D',
            'sumativa3_d' => 'Sumativa3 D',
            'nota_d' => 'Nota D',
            'suma_total' => 'Suma Total',
            'final_homologado' => 'Final Homologado',
            'creado' => 'Creado',
            'usuario_crea' => 'Usuario Crea',
            'actualizado' => 'Actualizado',
            'usuario_modifica' => 'Usuario Modifica',
        ];
    }
}
