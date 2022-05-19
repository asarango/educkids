<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_institute_authorities".
 *
 * @property int $id
 * @property string $usuario
 * @property string $cargo_codigo
 * @property string $cargo_descripcion
 * @property string $titulo
 * @property string $seccion
 * @property bool $es_activo
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property Usuario $usuario0
 */
class OpInstituteAuthorities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_institute_authorities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario', 'cargo_codigo', 'cargo_descripcion', 'titulo', 'seccion', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['es_activo'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['usuario', 'created', 'updated'], 'string', 'max' => 200],
            [['cargo_codigo', 'seccion'], 'string', 'max' => 30],
            [['cargo_descripcion'], 'string', 'max' => 50],
            [['titulo'], 'string', 'max' => 80],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'cargo_codigo' => 'Cargo Codigo',
            'cargo_descripcion' => 'Cargo Descripcion',
            'titulo' => 'Titulo',
            'seccion' => 'Seccion',
            'es_activo' => 'Es Activo',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'usuario']);
    }
}
