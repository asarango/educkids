<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo".
 *
 * @property int $id
 * @property int $materia_id
 * @property string $tipo_referencia
 * @property string $codigo
 * @property string $detalle
 * @property bool $imprencindible
 * @property int $bloque_id
 * @property string $campo_aux1
 * @property string $campo_aux2
 * @property string $pertence_a
 *
 * @property GenMallaMateria $materia
 */
class CurCurriculo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['materia_id', 'tipo_referencia', 'detalle'], 'required'],
            [['materia_id', 'bloque_id'], 'default', 'value' => null],
            [['materia_id', 'bloque_id'], 'integer'],
            [['detalle', 'campo_aux1', 'campo_aux2'], 'string'],
            [['imprencindible'], 'boolean'],
            [['tipo_referencia'], 'string', 'max' => 50],
            [['codigo', 'pertence_a'], 'string', 'max' => 15],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenMallaMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'materia_id' => 'Materia ID',
            'tipo_referencia' => 'Tipo Referencia',
            'codigo' => 'Codigo',
            'detalle' => 'Detalle',
            'imprencindible' => 'Imprencindible',
            'bloque_id' => 'Bloque ID',
            'campo_aux1' => 'Campo Aux1',
            'campo_aux2' => 'Campo Aux2',
            'pertence_a' => 'Pertence A',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(GenMallaMateria::className(), ['id' => 'materia_id']);
    }
}
