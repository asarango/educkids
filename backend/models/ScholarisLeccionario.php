<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_leccionario".
 *
 * @property int $paralelo_id
 * @property string $fecha
 * @property int $total_clases
 * @property int $total_revisadas
 * @property string $usuario_crea
 * @property string $fecha_crea
 * @property string $usuario_actualiza
 * @property string $fecha_actualiza
 *
 * @property OpCourseParalelo $paralelo
 */
class ScholarisLeccionario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_leccionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paralelo_id', 'fecha', 'total_clases', 'total_revisadas', 'usuario_crea', 'fecha_crea', 'usuario_actualiza', 'fecha_actualiza'], 'required'],
            [['paralelo_id', 'total_clases', 'total_revisadas'], 'default', 'value' => null],
            [['paralelo_id', 'total_clases', 'total_revisadas'], 'integer'],
            [['fecha', 'fecha_crea', 'fecha_actualiza'], 'safe'],
            [['usuario_crea', 'usuario_actualiza'], 'string', 'max' => 150],
            [['paralelo_id', 'fecha'], 'unique', 'targetAttribute' => ['paralelo_id', 'fecha']],
            [['paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['paralelo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paralelo_id' => 'Paralelo ID',
            'fecha' => 'Fecha',
            'total_clases' => 'Total Clases',
            'total_revisadas' => 'Total Revisadas',
            'usuario_crea' => 'Usuario Crea',
            'fecha_crea' => 'Fecha Crea',
            'usuario_actualiza' => 'Usuario Actualiza',
            'fecha_actualiza' => 'Fecha Actualiza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParalelo()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'paralelo_id']);
    }
}
