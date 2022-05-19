<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_firmas_reportes".
 *
 * @property int $id
 * @property int $template_id
 * @property string $codigo_reporte
 * @property string $principal_cargo
 * @property string $principal_nombre
 * @property string $secretaria_cargo
 * @property string $secretaria_nombre
 *
 * @property OpCourseTemplate $template
 */
class ScholarisFirmasReportes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_firmas_reportes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'codigo_reporte', 'principal_cargo', 'secretaria_cargo', 'instituto_id'], 'required'],
            [['template_id'], 'default', 'value' => null],
            [['template_id', 'instituto_id'], 'integer'],
            [['codigo_reporte', 'principal_cargo', 'secretaria_cargo'], 'string', 'max' => 50],
            [['principal_nombre', 'secretaria_nombre'], 'string', 'max' => 200],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Template ID',
            'codigo_reporte' => 'Codigo Reporte',
            'principal_cargo' => 'Principal Cargo',
            'principal_nombre' => 'Principal Nombre',
            'secretaria_cargo' => 'Secretaria Cargo',
            'secretaria_nombre' => 'Secretaria Nombre',
            'instituto_id' => 'Instituto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'template_id']);
    }
    
    
    public function getInstituto(){
        return $this->hasOne(OpInstitute::className(), ['id' => 'instituto_id']);
    }
    
    
    
}
