@extends ('email.layouts.main')

@section ('body')

<h1>{{ __('mail.user.password-reset.title') }}</h1>

<a href="{{ $url }}">{{ __('mail.user.password-reset.link') }}</a>

@stop
