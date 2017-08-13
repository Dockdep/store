<?php
    
    use artweb\artbox\modules\blog\models\BlogArticle;
    use artweb\artbox\modules\blog\models\BlogArticleLang;
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogTag;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\web\JsExpression;
    
    /**
     * @var View              $this
     * @var BlogArticle       $model
     * @var ActiveForm        $form
     * @var BlogArticleLang[] $modelLangs
     * @var BlogCategory[]    $categories
     * @var BlogTag[]         $tags
     * @var array             $products
     * @var array             $articles
     */
?>

<div class="blog-article-form">
    
    <?php $form = ActiveForm::begin(
        [
            'options' => [ 'enctype' => 'multipart/form-data' ],
        ]
    ); ?>
    
    <?php
        echo LanguageForm::widget(
            [
                'modelLangs' => $modelLangs,
                'formView'   => '@common/modules/blog/views/blog-article/_form_language',
                'form'       => $form,
            ]
        );
    ?>
    
    <?php
        echo $form->field($model, 'blogCategories')
                  ->widget(
                      Select2::className(),
                      [
                          'data'          => $categories,
                          'theme'         => Select2::THEME_BOOTSTRAP,
                          'options'       => [
                              'placeholder' => \Yii::t('blog', 'Select category'),
                              'multiple'    => true,
                          ],
                          'pluginOptions' => [
                              'allowClear' => true,
                          ],
                      ]
                  );
    ?>
    
    <?php
        echo $form->field($model, 'blogTags')
                  ->widget(
                      Select2::className(),
                      [
                          'data'          => $tags,
                          'theme'         => Select2::THEME_BOOTSTRAP,
                          'options'       => [
                              'placeholder' => \Yii::t('blog', 'Select tag'),
                              'multiple'    => true,
                          ],
                          'pluginOptions' => [
                              'allowClear' => true,
                          ],
                      ]
                  );
    ?>
    
    <?= $form->field($model, 'image')
             ->widget(
                 \kartik\file\FileInput::className(),
                 [
                     'language'      => 'ru',
                     'options'       => [
                         'accept'   => 'image/*',
                         'multiple' => false,
                     ],
                     'pluginOptions' => [
                         'allowedFileExtensions' => [
                             'jpg',
                             'gif',
                             'png',
                         ],
                         'initialPreview'        => !empty( $model->imageUrl ) ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage(
                             $model->imageUrl,
                             'list'
                         ) : '',
                         'overwriteInitial'      => true,
                         'showRemove'            => false,
                         'showUpload'            => false,
                         'previewFileType'       => 'image',
                     ],
                 ]
             ); ?>
    
    <?php
        echo $form->field($model, 'products')
                  ->widget(
                      Select2::className(),
                      [
                          'data'          => $products,
                          'options'       => [
                              'placeholder' => \Yii::t('blog', 'Select related products'),
                              'multiple'    => true,
                          ],
                          'pluginOptions' => [
                              'allowClear'         => true,
                              'minimumInputLength' => 3,
                              'language'           => [
                                  'errorLoading' => new JsExpression(
                                      "function () { return '" . \Yii::t('blog', 'Waiting for results') . "'; }"
                                  ),
                              ],
                              'ajax'               => [
                                  'url'      => yii\helpers\Url::to([ '/blog/blog-article/product-list' ]),
                                  'dataType' => 'json',
                                  'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                              ],
                              'templateResult'     => new JsExpression('function(product) { return product.text; }'),
                              'templateSelection'  => new JsExpression('function (product) { return product.text; }'),
                          ],
                      ]
                  );
    ?>
    
    <?php
        echo $form->field($model, 'blogArticles')
                  ->widget(
                      Select2::className(),
                      [
                          'data'          => $articles,
                          'options'       => [
                              'placeholder' => \Yii::t('blog', 'Select related articles'),
                              'multiple'    => true,
                          ],
                          'pluginOptions' => [
                              'allowClear'         => true,
                              'minimumInputLength' => 3,
                              'language'           => [
                                  'errorLoading' => new JsExpression(
                                      "function () { return '" . \Yii::t('blog', 'Waiting for results') . "'; }"
                                  ),
                              ],
                              'ajax'               => [
                                  'url'      => yii\helpers\Url::to([ '/blog/blog-article/article-list' ]),
                                  'dataType' => 'json',
                                  'data'     => new JsExpression(
                                      'function(params) { return {q:params.term, id:' . $model->id . '}; }'
                                  ),
                              ],
                              'templateResult'     => new JsExpression('function(article) { return article.text; }'),
                              'templateSelection'  => new JsExpression('function (article) { return article.text; }'),
                          ],
                      ]
                  );
    ?>
    
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?= $form->field($model, 'status')
             ->checkbox() ?>
    
    <?= $form->field($model, 'author_id')
             ->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
