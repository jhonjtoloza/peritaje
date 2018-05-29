<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Ingresar';
?>
<div class="col-md-6 col-md-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2 class="panel-title"><?= $this->title ?></h2>
    </div>
    <div class="panel-body">
      <div class="site-login">
        <?php $form = ActiveForm::begin([
          'id' => 'login-form',
          'layout' => 'horizontal',
          'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-5 control-label'],
          ],
        ]); ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox([
          'template' => "<div class=\"col-lg-offset-5 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-12 col-lg-offset-5\">{error}</div>",
        ]) ?>
        <div class="form-group">
          <div class="col-lg-offset-5 col-lg-7">
            <?= Html::submitButton('Ingresar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>

