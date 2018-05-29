<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RellenadoSearch represents the model behind the search form of `app\models\Rellenado`.
 */
class RellenadoSearch extends Rellenado
{
  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['id', 'seccion_id'], 'integer'],
      [['columna', 'espacio', 'opciones', 'val_opciones', 'label'], 'safe'],
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
  public function search($params)
  {
    $query = Rellenado::find();

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => false
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
      'seccion_id' => $this->seccion_id,
    ]);

    $query->andFilterWhere(['like', 'columna', $this->columna])
      ->andFilterWhere(['like', 'espacio', $this->espacio])
      ->andFilterWhere(['like', 'opciones', $this->opciones])
      ->andFilterWhere(['like', 'val_opciones', $this->val_opciones])
      ->andFilterWhere(['like', 'label', $this->label]);

    return $dataProvider;
  }
}
