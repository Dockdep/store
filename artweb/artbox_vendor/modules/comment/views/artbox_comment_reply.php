<?php
    use artweb\artbox\modules\comment\models\CommentModel;
    use yii\base\Model;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var CommentModel $comment_model
     * @var array        $form_params
     * @var Model        $model
     * @var string       $formId
     * @var View         $this
     */
    $text_input_id = Html::getInputId($comment_model, 'text') . '-reply';
    $artbox_comment_pid_input_id = Html::getInputId($comment_model, 'artbox_comment_pid') . '-reply';
    $text_input_selectors = [
        'container' => '.field-' . $text_input_id,
        'input'     => '#' . $text_input_id,
    ];
    $artbox_comment_pid_input_selectors = [
        'container' => '.field-' . $artbox_comment_pid_input_id,
        'input'     => '#' . $artbox_comment_pid_input_id,
    ];
    $form = ActiveForm::begin([
        'id'     => $formId . '-reply',
        'action' => Url::to([
            'artbox-comment/default/create',
            'entity' => $comment_model->encryptedEntity,
        ]),
    ]);
?>
    <div class="answer-form">
        <?php
            echo $form->field($comment_model, 'artbox_comment_pid', [
                'selectors'    => $artbox_comment_pid_input_selectors,
                'inputOptions' => [
                    'id'    => $artbox_comment_pid_input_id,
                    'class' => 'form-control',
                ],
            ])
                      ->hiddenInput()
                      ->label(false);
            echo $form->field($comment_model, 'text', [
                'selectors'    => $text_input_selectors,
                'inputOptions' => [
                    'id'    => $text_input_id,
                    'class' => 'form-control',
                    'cols'  => 30,
                    'rows'  => 10,
                ],
            ])
                      ->textarea();
            echo Html::submitButton(Yii::t('artbox-comment', 'Submit'));
            echo Html::button(Yii::t('artbox-comment', 'Cancel'), [ 'data-action' => 'reply-cancel' ]);
        ?>
    </div>
<?php
    ActiveForm::end();
?>