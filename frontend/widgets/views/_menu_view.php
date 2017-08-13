<?php
    /**
     * @var array $categories
     */
    use yii\helpers\Url;

?>

<div class="container">
    <div class="row">
        <div id="menu_ident" class="col-xs-4 col-md-4 col-lg-3">
            <div class="visible-xs hidden mob-catalog-close">КАТАЛОГ</div>
            <ul class="catalog-menu">
                <?php foreach ($categories as $category) {
                    if (isset( $category[ 'children' ] )) {
                        ?>
                        <li>
                            <span><?php echo $category[ 'lang' ][ 'title' ]; ?></span>
                            <ul>
                                <?php foreach ($category[ 'children' ] as $child) { ?>
                                    <li><a title="<?php echo $child[ 'lang' ][ 'title' ]; ?>" href="<?php echo Url::to(
                                            [
                                                'catalog/category',
                                                'category' => $child[ 'lang' ][ 'alias' ],
                                            ]
                                        ); ?>"><?php echo $child[ 'lang' ][ 'title' ]; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li><span><a href="<?php echo Url::to(
                                    [
                                        'catalog/category',
                                        'category' => $category[ 'lang' ][ 'alias' ],
                                    ]
                                ); ?>"><?php echo $category[ 'lang' ][ 'title' ]; ?></a></span></li>
                    <?php }
                } ?>
            </ul>
        </div>
    </div>
</div>