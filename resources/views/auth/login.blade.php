<!DOCTYPE html>
<!--
**********************************************************************************************************
Copyright (c) 2021 .
**********************************************************************************************************  -->
<!--
Template Name: Netflix - HTML Template
Version: 1.0.0
Author: Avalon Tribe
-->
<html lang="en">
  <!-- head -->
  <head>
    <!-- CSRF Token -->
    <title>Netflix</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="description" content="Netflix" />
    <meta name="keywords" content="Netflix, Netflix template, Netflix html template">
    <meta name="author" content="Avalon Tribe" />
    <meta name="MobileOptimized" content="320" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <!-- end theme style -->
    <script>
      window.Laravel =  <?php echo json_encode([
        'csrfToken' => csrf_token(),
      ]); ?>
    </script>
  </head>
  <!-- end head -->
  <!--body start-->
  <body>
    <!-- login -->
    <div id="app">
      <login></login>
    </div>
    <!-- end login -->
    <script src="{{ asset('js/app.js') }}" defer></script>

  </body>
  <!--body end -->
</html>