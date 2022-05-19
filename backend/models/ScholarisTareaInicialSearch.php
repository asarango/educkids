<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisTareaInicial;

/**
 * ScholarisTareaInicialSearch represents the model behind the search form about `backend\models\ScholarisTareaInicial`.
 */
class ScholarisTareaInicialSearch extends ScholarisTareaInicial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'clase_id'], 'integer'],
            [['quimestre_codigo', 'titulo', 'fecha_inicio', 'fecha_entrega', 
                'nombre_archivo', 'creado_por', 'creado_fecha', 
                'actualizado_por', 'actualizado_fecha',
                'tipo_material','link_videoconferencia','respaldo_videoconferencia'], 'safe'],
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
    public function search($params, $clase, $quimestre)
    {
        $query = ScholarisTareaInicial::find()
                ->where([
                    'clase_id' => $clase,
                    'quimestre_codigo' => $quimestre
                ]);

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
            'clase_id' => $this->clase_id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_entrega' => $this->fecha_entrega,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['like', 'quimestre_codigo', $this->quimestre_codigo])
            ->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'nombre_archivo', $this->nombre_archivo])
            ->andFilterWhere(['like', 'creado_por', $this->creado_por])
            ->andFilterWhere(['like', 'tipo_material', $this->tipo_material])
            ->andFilterWhere(['like', 'link_videoconferencia', $this->link_videoconferencia])
            ->andFilterWhere(['like', 'respaldo_videoconferencia', $this->respaldo_videoconferencia])
            ->andFilterWhere(['like', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}
