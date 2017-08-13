<?php

/* @var $this \yii\web\View */
    use backend\assets\AppAsset;
    use yii\web\View;
    
    /* @var $content string */
    
AppAsset::register($this);

$js = <<< JS
if( localStorage.getItem('closed') == 'yes' ) {
        document.body.className += " sidebar-collapse";
    }

$(document).on('click', 'a.sidebar-toggle', function() {
    var closed = localStorage.getItem('closed');
    if (closed != 'yes') {
       localStorage.setItem('closed', 'yes');
       } else {
       localStorage.setItem('closed', 'no');
       }
});
JS;

$this->registerJs($js, View::POS_READY);
?>
<header class="main-header">
    <!-- Logo -->
    <a href="/admin/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>BOX</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Art</b>BOX</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top collapsed" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
        </div>
    </nav>
</header>