<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 29/05/18
 * Time: 12:38 PM
 */

/* @var $this \yii\web\View */
/* @var $model \app\models\FileUpload */
?>
<div class="col-lg-6 col-lg-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2 class="panel-title">Logo para el excel</h2>
    </div>
    <div class="panel-body">
      <?php $form = \yii\widgets\ActiveForm::begin() ?>
      <?= $form->field($model, 'picture')->fileInput() ?>
      <div class="form-group">
        <?= \yii\helpers\Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
      </div>
      <?php \yii\widgets\ActiveForm::end() ?>
    </div>
  </div>
</div>
