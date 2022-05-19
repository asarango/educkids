<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisCursoImprimeLibreta;

/**
 * ScholarisCursoImprimeLibretaSearch represents the model behind the search form about `backend\models\ScholarisCursoImprimeLibreta`.
 */
class ScholarisCursoImprimeLibretaSearch extends ScholarisCursoImprimeLibreta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'curso_id', 'rinde_supletorio'], 'integer'],
            [['imprime', 'tipo_proyectos','esta_bloqueado'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function search($params, $periodo, $institutoId)
    {
        $query = ScholarisCursoImprimeLibreta::find()
                ->innerJoin("op_course c","c.id = scholaris_curso_imprime_libreta.curso_id")
                ->innerJoin("op_section s","s.id = c.section")
                ->innerJoin("op_period op","op.id = s.period_id")
                ->innerJoin("scholaris_op_period_periodo_scholaris sop","sop.op_id = s.period_id")
                ->innerJoin("scholaris_periodo p","p.id = sop.scholaris_id")
                ->where(['p.id' => $periodo, 'op.institute' => $institutoId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'curso_id' => $this->curso_id,
            'rinde_supletorio' => $this->rinde_supletorio,
            'esta_bloqueado' => $this->esta_bloqueado,
        ]);

        $query->andFilterWhere(['like', 'imprime', $this->imprime]);
        $query->andFilterWhere(['like', 'tipo_proyectos', $this->tipo_proyectos]);

        return $dataProvider;
    }
}
