<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_profesor".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $hora_id
 * @property string $hora_ingresa
 * @property string $fecha
 * @property int $user_id
 * @property string $creado
 * @property string $modificado
 * @property int $estado
 *
 * @property ScholarisAsistenciaAlumnosNovedades[] $scholarisAsistenciaAlumnosNovedades
 * @property ScholarisAsistenciaClaseTema[] $scholarisAsistenciaClaseTemas
 * @property ScholarisClase $clase
 */
class ScholarisAsistenciaProfesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_profesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'hora_id', 'hora_ingresa', 'fecha', 'user_id', 'creado', 'modificado', 'estado'], 'required'],
            [['clase_id', 'hora_id', 'user_id', 'estado'], 'default', 'value' => null],
            [['clase_id', 'hora_id', 'user_id', 'estado'], 'integer'],
            [['hora_ingresa', 'fecha', 'creado', 'modificado'], 'safe'],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'hora_id' => 'Hora ID',
            'hora_ingresa' => 'Hora Ingresa',
            'fecha' => 'Fecha',
            'user_id' => 'User ID',
            'creado' => 'Creado',
            'modificado' => 'Modificado',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaAlumnosNovedades()
    {
        return $this->hasMany(ScholarisAsistenciaAlumnosNovedades::className(), ['asistencia_profesor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaClaseTemas()
    {
        return $this->hasMany(ScholarisAsistenciaClaseTema::className(), ['asistencia_profesor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
