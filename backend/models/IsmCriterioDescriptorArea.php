<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_criterio_descriptor_area".
 *
 * @property int $id
 * @property int $id_area
 * @property int $id_curso
 * @property int $id_criterio
 * @property int $id_literal_criterio
 * @property int $id_descriptor
 * @property int $id_literal_descriptor
 *
 * @property IsmArea $area
 * @property IsmCriterio $criterio
 * @property IsmCriterioLiteral $literalCriterio
 * @property IsmDescriptores $descriptor
 * @property IsmLiteralDescriptores $literalDescriptor
 * @property OpCourseTemplate $curso
 * @property PlanificacionVerticalPaiDescriptores[] $planificacionVerticalPaiDescriptores
 */
class IsmCriterioDescriptorArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_criterio_descriptor_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_area', 'id_curso', 'id_criterio', 'id_literal_criterio', 'id_descriptor', 'id_literal_descriptor'], 'required'],
            [['id_area', 'id_curso', 'id_criterio', 'id_literal_criterio', 'id_descriptor', 'id_literal_descriptor'], 'default', 'value' => null],
            [['id_area', 'id_curso', 'id_criterio', 'id_literal_criterio', 'id_descriptor', 'id_literal_descriptor'], 'integer'],
            [['id_area'], 'exist', 'skipOnError' => true, 'targetClass' => IsmArea::className(), 'targetAttribute' => ['id_area' => 'id']],
            [['id_criterio'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterio::className(), 'targetAttribute' => ['id_criterio' => 'id']],
            [['id_literal_criterio'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterioLiteral::className(), 'targetAttribute' => ['id_literal_criterio' => 'id']],
            [['id_descriptor'], 'exist', 'skipOnError' => true, 'targetClass' => IsmDescriptores::className(), 'targetAttribute' => ['id_descriptor' => 'id']],
            [['id_literal_descriptor'], 'exist', 'skipOnError' => true, 'targetClass' => IsmLiteralDescriptores::className(), 'targetAttribute' => ['id_literal_descriptor' => 'id']],
            [['id_curso'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['id_curso' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_area' => 'Id Area',
            'id_curso' => 'Id Curso',
            'id_criterio' => 'Id Criterio',
            'id_literal_criterio' => 'Id Literal Criterio',
            'id_descriptor' => 'Id Descriptor',
            'id_literal_descriptor' => 'Id Literal Descriptor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(IsmArea::className(), ['id' => 'id_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterio()
    {
        return $this->hasOne(IsmCriterio::className(), ['id' => 'id_criterio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLiteralCriterio()
    {
        return $this->hasOne(IsmCriterioLiteral::className(), ['id' => 'id_literal_criterio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptor()
    {
        return $this->hasOne(IsmDescriptores::className(), ['id' => 'id_descriptor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLiteralDescriptor()
    {
        return $this->hasOne(IsmLiteralDescriptores::className(), ['id' => 'id_literal_descriptor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'id_curso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionVerticalPaiDescriptores()
    {
        return $this->hasMany(PlanificacionVerticalPaiDescriptores::className(), ['descriptor_id' => 'id']);
    }
}
