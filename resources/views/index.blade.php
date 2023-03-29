<!DOCTYPE html>
<!--
**********************************************************************************************************
    Copyright (c) 2022 .
**********************************************************************************************************  -->
<!-- 
Template Name: Netflix - HTML Template
Version: 1.0.0
Author: Success time
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]> -->
<html lang="en">
<!-- <![endif]-->
<!-- head -->
<head>
<title>{{ config('app.name') }}</title>
<meta charset="utf-8" />
<link href="{{ asset('images/icons/logo.png') }}" type="image/png" rel="icon"> <!-- favicon -->

<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- FontAwesome css -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>


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
    <v-main>
      @if (Auth::check())
        @if (Auth::user()->is_admin == 1)
          <admin-layout :auth="{{auth()->user()}}" :wfee="{{ $wfee }}" :rewardthreshold="{{ $rewardThreshold }}"></admin-layout>
        @else
          <user-layout :auth="{{auth()->user()}}" :wfee="{{ $wfee }}" :rewardthreshold="{{ $rewardThreshold }}"></user-layout>
        @endif
      @else
        <user-layout :auth="null" :wfee="0" :rewardthreshold="0"></user-layout>
      @endif
    </v-main>
  </div>
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js"></script>
</body>
<!--body end -->
</html>