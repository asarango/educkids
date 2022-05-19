<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_archivos_pud".
 *
 * @property int $id
 * @property string $codigo
 * @property int $bloque_id
 * @property int $clase_id
 * @property string $nombre
 * @property string $tipo_documento
 * @property string $estado
 * @property string $creado_fecha
 * @property string $creado_por
 * @property string $actualizado_fecha
 * @property string $actualizado_por
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisClase $clase
 */
class ScholarisArchivosPud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_archivos_pud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'bloque_id', 'clase_id', 'nombre', 'tipo_documento', 'estado', 'creado_fecha', 'creado_por', 'actualizado_fecha', 'actualizado_por'], 'required'],
            [['bloque_id', 'clase_id'], 'default', 'value' => null],
            [['bloque_id', 'clase_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['codigo'], 'string', 'max' => 80],
            [['nombre'], 'string', 'max' => 100],
            [['tipo_documento', 'estado'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['codigo'], 'unique'],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
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
            'codigo' => 'Codigo',
            'bloque_id' => 'Bloque ID',
            'clase_id' => 'Clase ID',
            'nombre' => 'Nombre',
            'tipo_documento' => 'Tipo Documento',
            'estado' => 'Estado',
            'creado_fecha' => 'Creado Fecha',
            'creado_por' => 'Creado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
            'actualizado_por' => 'Actualizado Por',
        ];
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
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
