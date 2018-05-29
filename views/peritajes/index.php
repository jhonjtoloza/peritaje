<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 19/04/18
 * Time: 07:35 PM
 */

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $data \yii\data\ArrayDataProvider */
$this->title = 'Listado de peritajes';
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">Listado de peritajes</h2>
  </div>
  <div class="panel-body">
    <?= Html::a('Exportar excel', \yii\helpers\ArrayHelper::merge(['peritajes/export-page'], Yii::$app->request->get()),['class'=>'btn btn-primary pull-right']) ?>
    <?= \yii\grid\GridView::widget([
      'dataProvider' => $data,
      'columns' => [
        "id",
        "reviso",
        "fecha",
        "eloboro",
        "registro_numero",
        "contrato_numero",
        [
          'header' => 'Generar excel',
          'value' => function ($model) {
            return
              Html::tag('div',
                Html::a('EXCEL', ['peritajes/generate-excel', 'id' => $model['id']], ['class' => 'btn btn-success btn-xs'])
                . Html::a('VER', ['peritajes/ver', 'id' => $model['id']], ['class' => 'btn btn-primary btn-xs'])
                . Html::a('BORRAR', ['peritajes/borrar', 'id' => $model['id']], ['class' => 'btn btn-danger btn-xs',
                  'data-confirm' => 'Seguro desea borrar el registro, esto sera irreversible?'])
                , ['class' => 'btn-group']);
          },
          'format' => 'raw'
        ]
      ]
    ]) ?>
  </div>
</div>
