@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login - Farm Angel | Upstream')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-notify.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-notify.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function () {
      @foreach (session('flash_notification', collect())->toArray() as $message)
          var type = "{{ $message['level'] }}";
          var message = "{{ $message['message'] }}";
          notify(type, message);
      @endforeach

      function notify(type, message) {
          $.notify({
              // options
              message: message,
          }, {
              // settings
              showProgressbar: true,
              delay: 2500,
              mouse_over: "pause",
              placement: {
                  from: "bottom",
                  align: "right",
              },
              animate: {
                  enter: "animated fadeInUp",
                  exit: "animated fadeOutDown",
              },
              type: type,
              template: '<div data-notify="container" class="aiz-notify alert alert-{0}" role="alert">' +
                  '<button type="button" aria-hidden="true" data-notify="dismiss" class="close"><i class="las la-times"></i></button>' +
                  '<span data-notify="message">{2}</span>' +
                  '<div class="progress" data-notify="progressbar">' +
                  '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                  "</div>" +
                  "</div>",
          });
      }
  });
</script>
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Login -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
            <span class="app-brand-logo demo">
              <img src="{{ asset('/images/farm-hero-larger.png') }}" width="100px" class="me-2">
            </span>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">

          @include('shared.form-alerts')

          <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autofocus value="{{ old('username')}}">
              <label for="username">Username</label>
            </div>
            <div class="mb-3">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn text-white d-grid w-100" type="submit" style="background-color:#2E7F25">Sign in</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Login -->
      <img alt="mask" src="{{asset('assets/img/illustrations/auth-basic-login-mask-'.$configData['style'].'.png') }}" class="authentication-image d-none d-lg-block" data-app-light-img="illustrations/auth-basic-login-mask-light.png" data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
    </div>
  </div>
</div>

<style>
  .position-relative {
    background-image: url('/images/login-background.jpg');
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>
@endsection
