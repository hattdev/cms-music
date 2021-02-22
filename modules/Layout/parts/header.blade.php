<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="auth-token" content="{{ Auth::user()->api_token??"" }}">
	<meta name="auth-name" content="{{ Auth::user()->name??"" }}">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="favicon.ico">
	<title>CMS</title>

	<!-- Bootstrap core CSS -->
	<link href="{{asset("dist/css/all.min.css?version=".time())}}" rel="stylesheet">
	<link href="{{asset("css/app.css?version=".time())}}" rel="stylesheet">

	<!-- Scripts -->
{{--	<script src="{{ asset('js/app.js') }}" defer></script>--}}
	<!-- Custom styles for this template -->
	@yield('script-header')
</head>
<body class="bg-light {{$body_class??""}}">
