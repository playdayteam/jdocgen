<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sources */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sources-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dir')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
