<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisArchivosprofesor;

/**
 * ScholarisArchivosprofesorSearch represents the model behind the search form of `frontend\models\ScholarisArchivosprofesor`.
 */
class ScholarisArchivosprofesorSearch extends ScholarisArchivosprofesor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idactividad'], 'integer'],
            [['archivo', 'fechasubido', 'nombre_archivo'], 'safe'],
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
    public function search($params, $id)
    {
        $query = ScholarisArchivosprofesor::find()
                 ->where(['idactividad' => $id]);

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
            'idactividad' => $this->idactividad,
            'fechasubido' => $this->fechasubido,
        ]);

        $query->andFilterWhere(['ilike', 'archivo', $this->archivo])
            ->andFilterWhere(['ilike', 'nombre_archivo', $this->nombre_archivo]);

        return $dataProvider;
    }
}
