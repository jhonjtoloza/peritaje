<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 9/05/18
 * Time: 10:09 AM
 */

/* @var $this \yii\web\View */
/* @var $model \app\models\FireUser */
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">Crear nuevo usuario de aplicación</h2>
  </div>
  <div class="panel-body">
    <?php $form = \yii\bootstrap\ActiveForm::begin() ?>
    <?= $form->field($model, 'displayName') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'phoneNumber') ?>
    <?= $form->field($model, 'password') ?>
    <div class="form-group">
      <?= \yii\helpers\Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end() ?>
  </div>
</div>