<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMecV2MallaCurso;

/**
 * ScholarisMecV2MallaCursoSearch represents the model behind the search form of `backend\models\ScholarisMecV2MallaCurso`.
 */
class ScholarisMecV2MallaCursoSearch extends ScholarisMecV2MallaCurso
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_id', 'curso_id'], 'integer'],
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
    public function search($params, $mallaId)
    {
        $query = ScholarisMecV2MallaCurso::find()
                ->where(['malla_id' => $mallaId]);

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
            'malla_id' => $this->malla_id,
            'curso_id' => $this->curso_id,
        ]);

        return $dataProvider;
    }
}
