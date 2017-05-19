<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Expired Password';
$this->breadcrumbs=array(
	'Expired Password',
);
?>

<h1>Expired Password</h1>

<p>Please fill out the following form:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'newPassword'); ?>
        <?php echo $form->passwordField($model,'newPassword'); ?>
        <?php echo $form->error($model,'newPassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'newPasswordRepeat'); ?>
        <?php echo $form->passwordField($model,'newPasswordRepeat'); ?>
        <?php echo $form->error($model,'newPasswordRepeat'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Set new password'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
