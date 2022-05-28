<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_pca".
 *
 * @property int $id
 * @property int $ism_area_materia_id
 * @property int $carga_horaria_semanal
 * @property int $numero_semanas_trabajo
 * @property int $imprevistos
 * @property string $objetivos
 * @property string $observaciones
 * @property string $bibliografia
 * @property string $estado
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property IsmAreaMateria $ismAreaMateria
 */
class KidsPca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_pca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ism_area_materia_id', 'estado', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['ism_area_materia_id', 'carga_horaria_semanal', 'numero_semanas_trabajo', 'imprevistos'], 'default', 'value' => null],
            [['ism_area_materia_id', 'carga_horaria_semanal', 'numero_semanas_trabajo', 'imprevistos'], 'integer'],
            [['objetivos', 'observaciones', 'bibliografia'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['estado'], 'string', 'max' => 20],
            [['created', 'updated'], 'string', 'max' => 200],
            [['ism_area_materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmAreaMateria::className(), 'targetAttribute' => ['ism_area_materia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ism_area_materia_id' => 'Ism Area Materia ID',
            'carga_horaria_semanal' => 'Carga Horaria Semanal',
            'numero_semanas_trabajo' => 'Numero Semanas Trabajo',
            'imprevistos' => 'Imprevistos',
            'objetivos' => 'Objetivos',
            'observaciones' => 'Observaciones',
            'bibliografia' => 'Bibliografia',
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMateria()
    {
        return $this->hasOne(IsmAreaMateria::className(), ['id' => 'ism_area_materia_id']);
    }
}
