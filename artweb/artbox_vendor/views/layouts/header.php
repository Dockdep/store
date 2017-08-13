<?php

/* @var $this \yii\web\View */
    use artweb\artbox\assets\AppAsset;
    
    /* @var $content string */
    
AppAsset::register($this);
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
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
        </div>
    </nav>
</header>