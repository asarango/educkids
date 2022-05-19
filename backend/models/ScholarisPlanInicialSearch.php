<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisPlanInicial;

/**
 * ScholarisPlanInicialSearch represents the model behind the search form of `backend\models\ScholarisPlanInicial`.
 */
class ScholarisPlanInicialSearch extends ScholarisPlanInicial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clase_id','orden'], 'integer'],
            [['quimestre_codigo', 'codigo_destreza', 'destreza_original', 'destreza_desagregada', 'estado', 'codigo_ambito'], 'safe'],
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
        $query = ScholarisPlanInicial::find();

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
        ]);

        $query->andFilterWhere(['ilike', 'quimestre_codigo', $this->quimestre_codigo])
            ->andFilterWhere(['ilike', 'codigo_destreza', $this->codigo_destreza])
            ->andFilterWhere(['ilike', 'destreza_original', $this->destreza_original])
            ->andFilterWhere(['ilike', 'destreza_desagregada', $this->destreza_desagregada])
            ->andFilterWhere(['ilike', 'codigo_ambito', $this->destreza_desagregada])
            ->andFilterWhere(['ilike', 'estado', $this->estado]);

        return $dataProvider;
    }
}
