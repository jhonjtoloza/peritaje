<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 9/05/18
 * Time: 10:00 AM
 */

/* @var $this \yii\web\View */
/* @var $users \yii\data\ArrayDataProvider */
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">Usuarios de a aplicación</h2>
  </div>
  <div class="panel-body">
    <?= \yii\grid\GridView::widget([
      'dataProvider' => $users,
      'columns' => [
        [
          'label' => 'Nombre',
          'value' => 'displayName',
        ],
        'email',
        [
          'label' => 'Telefono',
          'value' => 'phoneNumber'
        ],
        [
          'label' => 'Opciones',
          'value' => function ($model) {
            return \yii\helpers\Html::a('Editar', ['user-app/edit', 'id' => $model->uid], ['class' => 'btn btn-default']) .
              \yii\helpers\Html::a('Borrar', ['user-app/delete', 'id' => $model->uid], ['class' => 'btn btn-danger',
                'data-confirm' => 'Seguro desea eliminar este usuario de la aplicacion.?']);
          },
          'format' => 'raw'
        ]
      ]
    ]) ?>
  </div>
</div>
