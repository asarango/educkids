<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_homologacion".
 *
 * @property int $id
 * @property int $distribucion_id
 * @property string $tipo
 * @property int $codigo_tipo
 * @property string $nombre_tipo
 * @property string $profesor_nombre
 *
 * @property ScholarisMecV2Distribucion $distribucion
 */
class ScholarisMecV2Homologacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_homologacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distribucion_id', 'tipo', 'codigo_tipo', 'nombre_tipo'], 'required'],
            [['distribucion_id', 'codigo_tipo'], 'default', 'value' => null],
            [['distribucion_id', 'codigo_tipo'], 'integer'],
            [['tipo'], 'string', 'max' => 10],
            [['nombre_tipo', 'profesor_nombre'], 'string', 'max' => 100],
            [['distribucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Distribucion::className(), 'targetAttribute' => ['distribucion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'distribucion_id' => 'Distribucion ID',
            'tipo' => 'Tipo',
            'codigo_tipo' => 'Codigo Tipo',
            'nombre_tipo' => 'Nombre Tipo',
            'profesor_nombre' => 'Profesor Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistribucion()
    {
        return $this->hasOne(ScholarisMecV2Distribucion::className(), ['id' => 'distribucion_id']);
    }
}
