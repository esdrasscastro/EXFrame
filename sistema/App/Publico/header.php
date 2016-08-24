<?php
/**
 * Created by Grupo B+M
 * User: Esdras Castro
 * Date: 17/08/2016
 */
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- CSS -->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
        <!-- JavaScript -->
        <!--<script src="../../../js/jquery-3.0.0.min.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
        <title><?=self::$title;?></title>
        <style>body {display: flex;min-height: 100vh;flex-direction: column;}main{flex: 1 0 auto;}<?=self::getStyleScript('styleScript');?></style>

        <?=self::getStyleScript('css');?>
    </head>
    <body>
        <header>
            <nav class="green">
                <div class="nav-wrapper">
                    <a href="#" data-activates="navbar_default" class="button-collapse"><i class="material-icons">menu</i></a>
                    <?=self::getMenuDesktop();?>
                    <?=self::getMenuMobile();?>
                </div>
            </nav>
        </header>
