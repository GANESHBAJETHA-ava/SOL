<!DOCTYPE html>
<!--
**********************************************************************************************************
    Copyright (c) 2022 .
**********************************************************************************************************  -->
<!-- 
Template Name: Netflix - HTML Template
Version: 1.0.0
Author: Star of luck
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]> -->
<html lang="en">
<!-- <![endif]-->
<!-- head -->
<head>
<title>Login</title>
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<link href="{{ asset('images/icons/star.png') }}" type="image/png" rel="icon"> <!-- favicon -->

{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<meta name="description" content="Starofluck" />
<meta name="keywords" content="Starofluck" />
<meta name="author" content="Starofluck" />
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
<!-- FontAwesome css -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/> 

<script>
  window.Laravel =  <?php echo json_encode([
    'csrfToken' => csrf_token(),
  ]); ?>
</script>
<!-- style css -->
</head>
<!-- end head -->
<!--body start-->
<body>
  <div id="app">
    <v-main class="p-0">
      <login></login> 
    </v-main>
  </div>
  <script src="{{ asset('js/app.js') }}" defer></script>
</body>
<!--body end -->
</html>