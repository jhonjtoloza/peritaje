<?php
/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 18/04/18
 * Time: 05:12 PM
 */
/* @var $this \yii\web\View */
/* @var $models \app\models\Rellenado[] */
/* @var $seccion array|mixed */
$modelGenerator = [];
$form = [];
foreach ($models as $model) {
$modelGenerator[$model->columna] = '\'\'';
$ops = \yii\helpers\Json::decode($model->val_opciones);
$tag = 'ion-input';
if ($model->opciones) {
$tag = 'ion-select';
}
$form[] = [
'label' => $model->label,
'name' => 'model.' . $model->columna,
'tag' => $tag,
'options' => $ops,
];
}
$modelGenerator['secciones'][] = [
'nombre' => $seccion,
'seccion_complete' => false
]

?>
<div class="panel panel-default">
<div class="panel-heading">
<h2 class="panel-title">Generador</h2>
</div>
<div class="panel-body">
<h3>Model</h3>
<pre>
<code class="json">
model = {
<?php foreach ($modelGenerator as $k => $modelG): ?>
<?= $k ?>:<?= is_array($modelG)? json_encode($modelG): $modelG ?>,
<?php endforeach; ?>
};
</code>
</pre>
<hr>
<h3>Formulario</h3>
<pre>
<code class="html">
&lt;form (ngSubmit)="save()"&gt;
<?php foreach ($form as $input): ?>
&lt;ion-item&gt;
&lt;ion-label stacked&gt;<?= $input['label'] ?>&lt;/ion-label&gt;
&lt;<?= $input['tag'] ?> name="<?= $input['name'] ?>" [(ngModel)]="<?= $input['name'] ?>"&gt;
<?php if ($input['tag'] == 'ion-select'):foreach ($input['options'] as $option): ?>
&lt;ion-option value="<?= $option['opcion'] ?>"&gt;<?= $option['opcion'] ?>&lt;/ion-option&gt;
<?php endforeach;
endif; ?>&lt;/<?= $input['tag'] ?>&gt;
&lt;/ion-item&gt;
<?php endforeach; ?>
&lt;ion-item&gt;
&lt;ion-label&gt;Sección completa?&lt;/ion-label&gt;
&lt;ion-toggle name="model.secciones.complete" [(ngModel)]="model.model.secciones.complete"&gt;&lt;/ion-toggle&gt;
&lt;/ion-item&gt;
&lt;ion-item&gt;
&lt;button type="submit" ion-button block&gt;Guardar&lt;/button&gt;
&lt;/ion-item&gt;
&lt;/form&gt;
</code>
</pre>
</div>
</div>
<?php $this->registerJs('
console.log($(\'pre code\'));
$(\'pre code\').each(function(i, block) {
console.log(i,block);
hljs.highlightBlock(block);
});
') ?>


