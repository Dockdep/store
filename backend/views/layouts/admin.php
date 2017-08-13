<?php
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

/* @var $content string */
$this->beginContent('@app/views/layouts/main.php');
?>

    <?= $this->render('header') ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?= $this->render('main-sidebar') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>

            <?= $content; ?>
        </section>
    </div><!-- /.content-wrapper -->
    <?= $this->render('footer') ?>



    <!-- Control Sidebar -->
    <?= $this->render('control-sidebar') ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
<?php $this->endContent() ?>