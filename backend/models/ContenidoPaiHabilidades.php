<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contenido_pai_habilidades".
 *
 * @property int $id
 * @property string $es_titulo1
 * @property string $orden_titulo2
 * @property string $es_titulo2
 * @property string $es_subtitulo
 * @property string $es_exploracion
 * @property string $en_titulo1
 * @property string $en_titulo2
 * @property string $en_subtitulo
 * @property string $en_exploracion
 * @property string $fr_titulo1
 * @property string $fr_titulo2
 * @property string $fr_subtitulo
 * @property string $fr_exploracion
 *
 * @property MapaEnfoquesPai[] $mapaEnfoquesPais
 */
class ContenidoPaiHabilidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contenido_pai_habilidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['es_titulo1', 'orden_titulo2', 'es_titulo2', 'es_subtitulo', 'es_exploracion'], 'required'],
            [['es_titulo2', 'es_exploracion', 'en_exploracion', 'fr_exploracion'], 'string'],
            [['es_titulo1', 'en_titulo1', 'en_titulo2', 'fr_titulo1', 'fr_titulo2'], 'string', 'max' => 50],
            [['orden_titulo2'], 'string', 'max' => 5],
            [['es_subtitulo', 'en_subtitulo', 'fr_subtitulo'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'es_titulo1' => 'Es Titulo1',
            'orden_titulo2' => 'Orden Titulo2',
            'es_titulo2' => 'Es Titulo2',
            'es_subtitulo' => 'Es Subtitulo',
            'es_exploracion' => 'Es Exploracion',
            'en_titulo1' => 'En Titulo1',
            'en_titulo2' => 'En Titulo2',
            'en_subtitulo' => 'En Subtitulo',
            'en_exploracion' => 'En Exploracion',
            'fr_titulo1' => 'Fr Titulo1',
            'fr_titulo2' => 'Fr Titulo2',
            'fr_subtitulo' => 'Fr Subtitulo',
            'fr_exploracion' => 'Fr Exploracion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapaEnfoquesPais()
    {
        return $this->hasMany(MapaEnfoquesPai::className(), ['pai_habilidad_id' => 'id']);
    }
}
