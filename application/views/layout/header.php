<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo base_url('public/img/favicon.ico'); ?>">
    <title><?php if(isset($PAGE_TITLE)) echo $PAGE_TITLE; else 'SergStroy';?></title>

    <meta name="description" content="Source code generated Orletchi Victor">
    <meta name="author" content="Orletchi Victor">

    <link href="<?php //echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!--  link href="css/style.css" rel="stylesheet" >-->
    <link href="<?php echo base_url('assets/css/icomoon.css'); ?>" rel="stylesheet" type="text/css" />
    <!--  link href="http://glyphsearch.com/bower_components/icomoon/dist/css/style.css" rel="stylesheet" type="text/css" />-->
    <link href="<?php echo base_url('assets/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- link href="<?php //echo base_url('assets/css/less/Font-Awesome/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css" /-->
    <!--  link href="<?php //echo base_url('assets/css/awesome-bootstrap-checkbox.css'); ?>" rel="stylesheet" type="text/css" /-->
    <!-- link href="<?php //echo base_url('assets/css/less/style.less'); ?>" rel="stylesheet/less" type="text/css" / -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css" />
   
    <!-- link href="<?php //echo base_url('assets/css/custom-theme/jquery-ui-1.10.0.custom.css'); ?>" rel="stylesheet" type="text/css"  / -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <!-- script src="<?php //echo base_url('assets/js/less.min.js'); ?>" type="text/javascript"></script -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if IE 7]>
    	<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome-ie7.min.css'); ?>" />
    <![endif]-->
    <!--[if lt IE 9]>
    	<link rel="stylesheet" type="text/css" href="<?php //echo base_url('assets/css/custom-theme/jquery.ui.1.10.0.ie.css'); ?>" />
      	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
  </head>
  <body class="<?php echo (isset($BODY_CLASS)? $BODY_CLASS: 'home');?>">