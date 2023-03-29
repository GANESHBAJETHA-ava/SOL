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
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="description" content="Netflix" />
    <meta name="keywords" content="Netflix, Netflix template, Netflix html template">
    <meta name="author" content="Avalon Tribe" />
    <meta name="MobileOptimized" content="320" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
      <register></register>
      {{-- <section class="sign-in-page">
        <div class="container">
          <div class="row justify-content-center align-items-center height-self-center">
            <div class="col-lg-5 col-md-12 align-self-center">
              <div class="sign-user_card ">
                <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                    <h3 class="mb-3 sign-in-title text-center">Sign in</h3>
                    <form class="mt-4" action="{{ route('login') }}" method="POST">
                      @csrf
                      <div class="form-group">
                        <input type="email" name="email" class="form-control mb-0  @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" placeholder="Enter email" autocomplete="off" required>
                        @error('email')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <input type="password" name="password" class="form-control mb-0 @error('password') is-invalid @enderror" id="password"  placeholder="Password" required>
                        @error('password')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="d-flex justify-content-end mb-4">
                        @if (Route::has('password.request'))
                          <a class="f-link" href="{{ route('password.request') }}">Forgot your password?</a>
                        @endif
                      </div>
                      <div class="sign-info">
                        <button type="submit" class="btn btn-block btn-danger">Sign in</button>
                        <div class="custom-control mt-2">
                          <input type="checkbox" class="custom-control-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                          <label class="custom-control-label text-white pt-1" for="remember">Remember Me</label>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="mt-3">
                  <div class="d-flex justify-content-center text-white">
                    Don't have an account? <a href="{{ route('register') }}" class="text-primary ml-2">Sign Up</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> --}}
    </div>
    <!-- end login -->
    <script src="{{ asset('js/app.js') }}" defer></script>

  </body>
  <!--body end -->
</html>