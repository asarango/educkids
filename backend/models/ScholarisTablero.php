<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tablero".
 *
 * @property int $clase_id
 * @property string $curso
 * @property string $paralelo
 * @property string $apellido_profesor
 * @property string $nombre_profesor
 * @property int $p1
 * @property int $p2
 * @property int $p3
 * @property int $ex1
 * @property int $p4
 * @property int $p5
 * @property int $p6
 * @property int $ex2
 *
 * @property ScholarisClase $clase
 */
class ScholarisTablero extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tablero';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'curso', 'paralelo'], 'required'],
            [['clase_id', 'p1', 'p2', 'p3', 'ex1', 'p4', 'p5', 'p6', 'ex2'], 'default', 'value' => null],
            [['clase_id', 'p1', 'p2', 'p3', 'ex1', 'p4', 'p5', 'p6', 'ex2'], 'integer'],
            [['curso', 'apellido_profesor', 'nombre_profesor'], 'string', 'max' => 100],
            [['paralelo'], 'string', 'max' => 30],
            [['clase_id'], 'unique'],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clase_id' => 'Clase ID',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'apellido_profesor' => 'Apellido Profesor',
            'nombre_profesor' => 'Nombre Profesor',
            'p1' => 'P1',
            'p2' => 'P2',
            'p3' => 'P3',
            'ex1' => 'Ex1',
            'p4' => 'P4',
            'p5' => 'P5',
            'p6' => 'P6',
            'ex2' => 'Ex2',
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
