<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rellenado */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rellenados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rellenado-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
      ],
    ]) ?>
  </p>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'id',
      'columna',
      'espacio',
      'opciones',
      [
        'label' => 'opciones',
        'value' => Html::tag('pre',
          Html::tag('code
          ', \yii\helpers\Json::encode(\yii\helpers\Json::decode($model->val_opciones)))),
        'format'=>'raw'
      ],
      'label',
      'seccion_id',
    ],
  ]) ?>

</div>
