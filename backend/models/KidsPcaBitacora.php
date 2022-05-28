<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_pca_bitacora".
 *
 * @property int $id
 * @property int $pca_id
 * @property string $fecha
 * @property string $a_quien
 * @property string $desde
 * @property string $accion
 * @property string $comentario
 *
 * @property ScholarisPeriodo $pca
 * @property Usuario $desde0
 */
class KidsPcaBitacora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_pca_bitacora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pca_id', 'fecha', 'desde', 'accion'], 'required'],
            [['pca_id'], 'default', 'value' => null],
            [['pca_id'], 'integer'],
            [['fecha'], 'safe'],
            [['comentario'], 'string'],
            [['a_quien', 'desde'], 'string', 'max' => 200],
            [['accion'], 'string', 'max' => 40],
            [['pca_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['pca_id' => 'id']],
            [['desde'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['desde' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pca_id' => 'Pca ID',
            'fecha' => 'Fecha',
            'a_quien' => 'A Quien',
            'desde' => 'Desde',
            'accion' => 'Accion',
            'comentario' => 'Comentario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPca()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'pca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesde0()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'desde']);
    }
}
