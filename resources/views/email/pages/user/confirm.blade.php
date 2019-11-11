@extends ('email.layouts.main')

@section ('body')

<h1>{{ __('mail.user.confirm.title') }}</h1>

<a href="{{ $url }}">{{ __('mail.user.confirm.link') }}</a>

@stop
