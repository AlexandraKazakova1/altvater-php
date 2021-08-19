<!DOCTYPE html>
<html lang="ua">
	<head>
		<meta charset="utf-8">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title>{{$page['title']}}</title>
		
		<meta name="keywords" content="{{$page['keywords']}}">
		<meta name="description" content="{{$page['description']}}">
		
	@if($settings['copyright'])
		<meta name="copyright" content="{!!$settings['copyright']!!}">
	@endif
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		
		<meta property="og:site_name"       content="{{$settings['appname']}}" />
		<meta property="og:title"           content="{{$page['title']}}" />
		<meta property="og:type"            content="website" />
		<meta property="og:description"     content="{{$page['description']}}" />
		<meta property="og:url"     		content="{{url()->current()}}" />
		
	@if($settings['google_api_key'])
		<meta name="google-site-verification"   content="{{$settings['google_api_key']}}">
	@endif
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		{!!$settings['head_code']!!}
	</head>
	<body>
		{!!$settings['body_code']!!}
		
		@yield('content')
		
		{!!$settings['footer_code']!!}
	</body>
</html>
