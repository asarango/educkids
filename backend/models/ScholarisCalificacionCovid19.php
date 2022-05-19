<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificacion_covid19".
 *
 * @property int $inscription_id
 * @property int $tipo_quimestre_id
 * @property string $padre
 * @property string $portafolio
 * @property string $contenido
 * @property string $presentacion
 * @property string $total
 *
 * @property OpStudentInscription $inscription
 * @property ScholarisQuimestreTipoCalificacion $tipoQuimestre
 */
class ScholarisCalificacionCovid19 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificacion_covid19';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inscription_id', 'tipo_quimestre_id'], 'required'],
            [['inscription_id', 'tipo_quimestre_id'], 'default', 'value' => null],
            [['inscription_id', 'tipo_quimestre_id'], 'integer'],
            [['padre', 'portafolio', 'contenido', 'presentacion', 'total'], 'number'],
            [['inscription_id', 'tipo_quimestre_id'], 'unique', 'targetAttribute' => ['inscription_id', 'tipo_quimestre_id']],
            [['inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['inscription_id' => 'id']],
            [['tipo_quimestre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisQuimestreTipoCalificacion::className(), 'targetAttribute' => ['tipo_quimestre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'inscription_id' => 'Inscription ID',
            'tipo_quimestre_id' => 'Tipo Quimestre ID',
            'padre' => 'Padre',
            'portafolio' => 'Portafolio',
            'contenido' => 'Contenido',
            'presentacion' => 'Presentacion',
            'total' => 'Total',
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
    public function getTipoQuimestre()
    {
        return $this->hasOne(ScholarisQuimestreTipoCalificacion::className(), ['id' => 'tipo_quimestre_id']);
    }
}
