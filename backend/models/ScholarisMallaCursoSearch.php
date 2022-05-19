<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMallaCurso;

/**
 * ScholarisMallaCursoSearch represents the model behind the search form of `backend\models\ScholarisMallaCurso`.
 */
class ScholarisMallaCursoSearch extends ScholarisMallaCurso
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
    public function search($params,$id)
    {
        $query = ScholarisMallaCurso::find()
                ->where(['malla_id' => $id]);

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
