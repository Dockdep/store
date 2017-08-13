<?php
    use artweb\artbox\modules\comment\models\CommentModel;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    /**
     * @var CommentModel $model
     */
    $statuses = [
        $model::STATUS_ACTIVE  => 'Активный',
        $model::STATUS_HIDDEN  => 'Скрытый',
        $model::STATUS_DELETED => 'Удаленный',
    ];
    $form = ActiveForm::begin();
    echo $form->field($model, 'text')
              ->textarea();
    echo $form->field($model, 'status')
              ->dropDownList($statuses);
    echo Html::submitButton('Обновить');
    $form->end();