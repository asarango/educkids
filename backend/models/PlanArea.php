<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_area".
 *
 * @property int $id
 * @property string $nombre
 * @property bool $en_ministerio
 */
class PlanArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'en_ministerio','color'], 'required'],
            [['en_ministerio'], 'boolean'],
            [['area_id'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['color'], 'string', 'max' => 10],
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
            'en_ministerio' => 'En Ministerio',
            'tipo_area' => 'Tipo Asignatura',
            'area_id' => 'Area',
            'color' => 'Color',
        ];
    }
    
    public function getArea(){
        return $this->hasOne(PlanAreaSup::className(), ['id' => 'area_id']);
    }
}
