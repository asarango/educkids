<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_califica_comportamiento".
 *
 * @property int $id
 * @property int $inscription_id
 * @property int $bloque_id
 * @property string $calificacion
 * @property string $observacion
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpStudentInscription $inscription
 * @property ScholarisBloqueActividad $bloque
 */
class ScholarisCalificaComportamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_califica_comportamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inscription_id', 'bloque_id', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['inscription_id', 'bloque_id'], 'default', 'value' => null],
            [['inscription_id', 'bloque_id'], 'integer'],
            [['calificacion'], 'number'],
            [['observacion'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 200],
            [['inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['inscription_id' => 'id']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inscription_id' => 'Inscription ID',
            'bloque_id' => 'Bloque ID',
            'calificacion' => 'Calificacion',
            'observacion' => 'Observacion',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInscription()
    {
        return $this->hasOne(OpStudentInscription::className(), ['id' => 'inscription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }
}
