<?php
    use artweb\artbox\modules\comment\models\CommentModel;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ListView;
    
    /**
     * @var CommentModel $model
     * @var mixed        $key
     * @var int          $index
     * @var ListView     $widget
     */
?>
<div class="comments-wr">
    <div class="artbox_item_info">
        <div class="user-ico">
            <?php
                echo Html::img('/img/user-noimage.png');
            ?>
        </div>
        <div class="user_data" itemprop="datePublished">
            <?php
                echo date('d.m.Y', $model->created_at);
            ?>
        </div>
        <div class="user_name" itemprop="author">
            <?php
                if(!empty( $model->user )) {
                    echo $model->user->username;
                } else {
                    echo $model->username . ' (' . Yii::t('artbox-comment', 'Guest') . ')';
                }
            ?>
        </div>
        <?php
            if(!empty( $model->rating )) {
                ?>
                <div class="user_rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <span itemprop="worstRating" style="display: none">1</span>
                    <span itemprop="ratingValue" style="display: none"><?php echo $model->rating->value; ?></span>
                    <span itemprop="bestRating" style="display: none">5</span>
                    <div class="rateit" data-rateit-value="<?php echo $model->rating->value; ?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                </div>
                <?php
            }
        ?>
        <div class="user_txt" itemprop="description">
            <?php
                echo $model->text;
            ?>
        </div>
    </div>
    <div class="artbox_item_tools comment-panel">
        <?php
            if(!\Yii::$app->user->isGuest) {
                ?>
                <a href="" class="btn-comm-answer" data-action="reply">Ответить</a>
                <?php
            }
            if(!\Yii::$app->user->isGuest && \Yii::$app->user->id == $model->user_id) {
                ?>
                <a href="" class="btn-comm-delete" data-action="delete" data-url="<?php echo Url::to([
                    'artbox-comment/default/delete',
                    'id' => $model->artbox_comment_id,
                ]); ?>">Удалить</a>
                <?php
            }
            // Like / dislike to be done
            /*
            ?>
            <a href="" class="btn-comm-like" data-action="like" data-url="<?php echo Url::to([
                    'artbox-comment/default/like',
                    'id' => $model->artbox_comment_id,
                ]); ?>">Like</a>
            <a href="" class="btn-comm-dislike" data-action="dislike" data-url="<?php echo Url::to([
                    'artbox-comment/default/dislike',
                    'id' => $model->artbox_comment_id,
                ]); ?>">Dislike</a>
            <?php
            */
        ?>
        <div class="artbox_item_reply"></div>
    </div>
</div>
<div class="artbox_children_container">
    <?php
        if(!empty( $model->children )) {
            foreach($model->children as $index => $child) {
                ?>
                <div class="artbox_child_container comment-answer">
                    <div class="artbox_child_info">
                        <div class="user-ico">
                            <?php
                                echo Html::img('/img/user-noimage.png');
                            ?>
                        </div>
                        <div class="user_data">
                            <?php
                                echo date('d.m.Y', $child->created_at);
                            ?>
                        </div>
                        <div class="user_name">
                            <?php
                                if(!empty( $child->user )) {
                                    echo $child->user->username;
                                } else {
                                    echo $child->username . ' (' . Yii::t('artbox-comment', 'Guest') . ')';
                                }
                            ?>
                        </div>
                        <div class="user_txt">
                            <?php
                                echo $child->text;
                            ?>
                        </div>
                    </div>
                    <div class="artbox_child_tools comment-panel">
                        <?php
                            if(!\Yii::$app->user->isGuest) {
                                ?>
                                <a href="" class="btn-comm-answer" data-action="reply">Ответить</a>
                                <?php
                            }
                            if(!\Yii::$app->user->isGuest && \Yii::$app->user->id == $child->user_id) {
                                ?>
                                <a href="" class="btn-comm-delete" data-action="delete" data-url="<?php echo Url::to([
                                    'artbox-comment/default/delete',
                                    'id' => $child->artbox_comment_id,
                                ]); ?>">Удалить</a>
                                <?php
                            }
                            /* Like /dislike to be done
                            ?>
                            <a href="" class="btn-comm-like" data-action="like" data-url="<?php echo Url::to([
                                    'artbox-comment/default/like',
                                    'id' => $child->artbox_comment_id,
                                ]); ?>">Like</a>
                            <a href="" class="btn-comm-dislike" data-action="dislike" data-url="<?php echo Url::to([
                                    'artbox-comment/default/dislike',
                                    'id' => $child->artbox_comment_id,
                                ]); ?>">Dislike</a>
                            <?php
                            */
                        ?>
                        <div class="artbox_child_reply"></div>
                    </div>
                </div>
                <?php
            }
        }
    ?>
</div>

