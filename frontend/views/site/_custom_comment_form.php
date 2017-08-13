<?php
    use artweb\artbox\comment\models\CommentModel;
    use artweb\artbox\comment\models\RatingModel;
    use yii\base\Model;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var CommentModel     $comment_model
     * @var array            $form_params
     * @var Model            $model
     * @var string           $formId
     * @var View             $this
     * @var RatingModel|NULL $rating_model
     */
    $form = ActiveForm::begin([
        'id'     => $formId,
        'action' => Url::to([
            'artbox-comment/default/create',
            'entity' => $comment_model->encryptedEntity,
        ]),
    ]);
?>
    <div class="form-comm-wr">
        <div class="style comment_form_name"><?php echo \Yii::t('app', 'Отзыв о товаре'); ?></div>
        <?php
            if(!empty( $rating_model )) {
                ?>
                <div class="input_bl stars-wr_">
                    <?php
                        echo $form->field($rating_model, 'value', [ 'enableClientValidation' => false ])
                                  ->hiddenInput()
                                  ->label(false);
                        echo Html::tag('div', '', [
                            'class'                  => 'rateit',
                            'data-rateit-backingfld' => '#' . Html::getInputId($rating_model, 'value'),
                        ]);
                    ?>
                </div>
                <?php
            }
            if(\Yii::$app->user->isGuest) {
                echo $form->field($comment_model, 'username', [ 'options' => [ 'class' => 'form-group input_bl' ] ])
                          ->textInput();
                echo $form->field($comment_model, 'email', [ 'options' => [ 'class' => 'form-group input_bl' ] ])
                          ->textInput();
            }
            echo $form->field($comment_model, 'text', [ 'options' => [ 'class' => 'form-group input_bl area_bl' ] ])
                ->label(\Yii::t('app', 'Отзыв'))
                      ->textarea();
            echo Html::tag('div', Html::submitButton(Yii::t('app', 'Оставить отзыв')), [ 'class' => 'input_bl submit_btn' ]);
        ?>
    </div>
<?php
    ActiveForm::end();
?>