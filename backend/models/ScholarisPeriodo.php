<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_periodo".
 *
 * @property int $id
 * @property int $usuario_creo
 * @property string $creado
 * @property string $nombre
 * @property int $usuario_actualizo
 * @property string $actualizado
 * @property string $codigo
 * @property bool $estado
 *
 * @property ScholarisInstitutoAutoridades[] $scholarisInstitutoAutoridades
 * @property ScholarisOpPeriodPeriodoScholaris[] $scholarisOpPeriodPeriodoScholaris
 * @property OpPeriod[] $ops
 */
class ScholarisPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_creo', 'usuario_actualizo'], 'default', 'value' => null],
            [['usuario_creo', 'usuario_actualizo'], 'integer'],
            [['creado', 'actualizado', 'codigo', 'estado'], 'required'],
            [['creado', 'actualizado','tipo_calificacion'], 'safe'],
            [['estado'], 'boolean'],
            [['nombre'], 'string', 'max' => 50],
            [['codigo'], 'string', 'max' => 20],
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
            'usuario_creo' => 'Usuario Creo',
            'creado' => 'Creado',
            'nombre' => 'Nombre',
            'usuario_actualizo' => 'Usuario Actualizo',
            'actualizado' => 'Actualizado',
            'codigo' => 'Codigo',
            'estado' => 'Estado',
            'tipo_calificacion' => 'Tipo Calificación',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisInstitutoAutoridades()
    {
        return $this->hasMany(ScholarisInstitutoAutoridades::className(), ['periodo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisOpPeriodPeriodoScholaris()
    {
        return $this->hasMany(ScholarisOpPeriodPeriodoScholaris::className(), ['scholaris_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOps()
    {
        return $this->hasMany(OpPeriod::className(), ['id' => 'op_id'])->viaTable('scholaris_op_period_periodo_scholaris', ['scholaris_id' => 'id']);
    }
}
