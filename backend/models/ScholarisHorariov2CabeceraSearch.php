<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisHorariov2Cabecera;

/**
 * ScholarisHorariov2CabeceraSearch represents the model behind the search form of `backend\models\ScholarisHorariov2Cabecera`.
 */
class ScholarisHorariov2CabeceraSearch extends ScholarisHorariov2Cabecera
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','periodo_id'], 'integer'],
            [['descripcion'], 'safe'],
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
    public function search($params, $periodo)
    {
        $query = ScholarisHorariov2Cabecera::find()
                ->where(['periodo_id' => $periodo]);

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
            'periodo_id' => $this->periodo_id,
        ]);

        $query->andFilterWhere(['ilike', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
