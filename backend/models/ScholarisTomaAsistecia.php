<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_toma_asistecia".
 *
 * @property int $id
 * @property int $paralelo_id
 * @property string $fecha
 * @property int $bloque_id
 * @property bool $hubo_clases
 * @property string $observacion
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpCourseParalelo $paralelo
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisTomaAsisteciaDetalle[] $scholarisTomaAsisteciaDetalles
 */
class ScholarisTomaAsistecia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_toma_asistecia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paralelo_id', 'fecha', 'bloque_id', 'hubo_clases', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['paralelo_id', 'bloque_id'], 'default', 'value' => null],
            [['paralelo_id', 'bloque_id'], 'integer'],
            [['fecha', 'creado_fecha', 'actualizado_fecha'], 'safe'],
            [['hubo_clases'], 'boolean'],
            [['observacion'], 'string'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['paralelo_id' => 'id']],
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
            'paralelo_id' => 'Paralelo ID',
            'fecha' => 'Fecha',
            'bloque_id' => 'Bloque ID',
            'hubo_clases' => 'Hubo Clases',
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
    public function getParalelo()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'paralelo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisTomaAsisteciaDetalles()
    {
        return $this->hasMany(ScholarisTomaAsisteciaDetalle::className(), ['toma_id' => 'id']);
    }
}
