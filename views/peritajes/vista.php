<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 14/05/18
 * Time: 12:58 PM
 */

/* @var $this \yii\web\View */
/* @var $coloumns array */
/* @var $model array|null */
$this->title = 'Peritaje ID: '.$model['id'];
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title"><?= $this->title ?></h2>
  </div>
  <div class="panel-body">
    <?= \yii\widgets\DetailView::widget([
      'model' => $model,
      'attributes' => $coloumns,
    ]) ?>
  </div>
</div>
<?php $this->registerCss(/** @lang CSS */
  "img{
      max-width: 50%;
    }") ?>
