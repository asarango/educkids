<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_instituto_autoridades".
 *
 * @property int $id
 * @property int $periodo_id
 * @property int $instituto_id
 * @property string $titulo
 * @property string $cargo
 * @property string $nombre
 * @property string $tipo_autoridad
 *
 * @property OpInstitute $instituto
 * @property ScholarisPeriodo $periodo
 */
class ScholarisInstitutoAutoridades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_instituto_autoridades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodo_id', 'instituto_id', 'titulo', 'cargo', 'nombre', 'tipo_autoridad'], 'required'],
            [['periodo_id', 'instituto_id'], 'default', 'value' => null],
            [['periodo_id', 'instituto_id'], 'integer'],
            [['titulo', 'cargo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 150],
            [['tipo_autoridad'], 'string', 'max' => 50],
            [['instituto_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['instituto_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periodo_id' => 'Periodo ID',
            'instituto_id' => 'Instituto ID',
            'titulo' => 'Titulo',
            'cargo' => 'Cargo',
            'nombre' => 'Nombre',
            'tipo_autoridad' => 'Tipo Autoridad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituto()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'instituto_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
