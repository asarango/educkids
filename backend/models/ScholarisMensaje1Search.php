<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMensaje1;

/**
 * ScholarisMensaje1Search represents the model behind the search form of `backend\models\ScholarisMensaje1`.
 */
class ScholarisMensaje1Search extends ScholarisMensaje1
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['mensaje', 'autor_usuario', 'para_usuario', 'fecha'], 'safe'],
            [['estado'], 'boolean'],
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
        $query = ScholarisMensaje1::find();

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
            'estado' => $this->estado,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'mensaje', $this->mensaje])
            ->andFilterWhere(['ilike', 'autor_usuario', $this->autor_usuario])
            ->andFilterWhere(['ilike', 'para_usuario', $this->para_usuario]);

        return $dataProvider;
    }
}
