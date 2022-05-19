<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_nivel".
 *
 * @property int $id
 * @property string $codigo
 * @property string $codigo_aux
 * @property string $nombre
 *
 * @property PlanNivelSub[] $planNivelSubs
 * @property OpCourseTemplate[] $cursoTemplates
 */
class PlanNivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_nivel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'codigo_aux', 'nombre'], 'required'],
            [['codigo', 'codigo_aux'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'codigo_aux' => 'Codigo Aux',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanNivelSubs()
    {
        return $this->hasMany(PlanNivelSub::className(), ['nivel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoTemplates()
    {
        return $this->hasMany(OpCourseTemplate::className(), ['id' => 'curso_template_id'])->viaTable('plan_nivel_sub', ['nivel_id' => 'id']);
    }
}
