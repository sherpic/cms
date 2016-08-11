@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Navigation</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-pencil', 'title'=>'Navigation'])
	<p>
		Different menus on pages
	</p>
	{!! $filter !!}
	{!! $grid !!}
	<a class='btn btn-primary' href="{{route('soda.'.$hint.'.create')}}"><span class="fa fa-plus"></span> Create</a>
@endsection