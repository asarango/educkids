<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScholarisAsistenciaProfesor;

/**
 * ScholarisAsistenciaProfesorSearch represents the model behind the search form of `app\models\ScholarisAsistenciaProfesor`.
 */
class ScholarisAsistenciaProfesorSearch extends ScholarisAsistenciaProfesor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id', 'hora_id', 'user_id', 'estado'], 'integer'],
            [['hora_ingresa', 'fecha', 'creado', 'modificado'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ScholarisAsistenciaProfesor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'clase_id' => $this->clase_id,
            'hora_id' => $this->hora_id,
            'hora_ingresa' => $this->hora_ingresa,
            'fecha' => $this->fecha,
            'user_id' => $this->user_id,
            'creado' => $this->creado,
            'modificado' => $this->modificado,
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }
}
