@extends ('email.layouts.main')

@section ('body')

<h1>{{ __('mail.user.signup.title') }}</h1>

<a href="{{ $url }}">{{ __('mail.user.signup.confirm-link') }}</a>

@stop
