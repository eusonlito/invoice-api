@extends ('email.layouts.main')

@section ('body')

<h1>{{ __('mail.form.contact.title') }}</h1>

@foreach ($data as $key => $value)
<p class="text-left"><strong>{{ ucfirst($key) }}</strong>: {{ $value }}</p>
@endforeach

@stop
