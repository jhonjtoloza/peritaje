<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RellenadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rellenados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rellenado-index">

  <h1><?= Html::encode($this->title) ?></h1>
  <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  <p>
    <?= Html::a('Create Rellenado', ['create'], ['class' => 'btn btn-success']) ?>
  </p>
  <?php \yii\bootstrap\ActiveForm::begin([
    'action' => ['rellenado/generate']
  ]) ?>
  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      [
        'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function ($model) {
        return ['value' => $model->id];
      },
      ],
      'id',
      'label',
      'columna',
      'espacio',
      'opciones:boolean',
      'val_opciones',
      //'seccion_id',
      ['class' => 'yii\grid\ActionColumn'],
    ],
  ]); ?>

  <?= Html::input('seccion', 'seccion', 'default', ['class' => 'form-control']) ?>
  <?= Html::submitButton("Generar modelado", ['class' => 'btn btn-primary']) ?>
  <?php \yii\bootstrap\ActiveForm::end() ?>
</div>
