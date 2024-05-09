<!DOCTYPE html>
<html lang="uk">
	<head>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-MH9GJK6V');</script>
		<!-- End Google Tag Manager -->

		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=AW-673981964"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'AW-673981964');
		</script>

		<meta charset="utf-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>{{$page['title']}}</title>

		<meta name="keywords" content="{{$page['keywords']}}">
		<meta name="description" content="{{$page['description']}}">

    @if($data->schema)
            <script type="application/ld+json">
{!!$data->schema!!}
            </script>
    @endif

	@if($settings['copyright'])
		<meta name="copyright" content="{!!$settings['copyright']!!}">
	@endif

	@if($canonical)
		<meta name="canonical" content="{{$canonical}}">
	@endif

	@if($robots)
		<meta name="robots" content="{{($robots == 'index' ? 'all' : 'noindex, nofollow')}}">
	@endif

	@if($settings['author'])
		<meta name="author" content="{{$settings['author']}}">
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

		<?php
			if($styles['header']){
				foreach($styles['header'] as $group){
					if($group['active'] && $group['files']){
						if((isset($group['page']) && $group['page']) && $group['page'][0] != 'all'){
							if(!in_array($page['uri'], $group['page'])){
								continue;
							}
						}

						echo $use->stylesheet($group['files'], $group['dir'], $group["media"], $group['minimize']);
					}
				}
			}

			if($scripts['header']){
				foreach($scripts['header'] as $group){
					if($group['active'] && $group['files']){
						if((isset($group['page']) && $group['page']) && $group['page'][0] != 'all'){
							if(!in_array($page['uri'], $group['page'])){
								continue;
							}
						}

						echo $use->javascript($group['files'], $group['dir'], $group['minimize']);
					}
				}
			}
		?>

		{!!$settings['head_code']!!}
	</head>
	<body>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MH9GJK6V"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->

		{!!$settings['body_code']!!}

		<header class="{{$headerClass}}">
			<nav class="header-nav container">
				<a href="/" class="header-nav__logo">
					<img src="/img/logo.png" alt="Veolia">
				</a>

				<ul class="header-nav__list">
					@foreach($menu as $item)
						<!-- -->
						<li class="header-nav__list__item">
							<a class="{{$item->class}}" href="{{url($item->url)}}">{{$item->title}}</a>
						</li>
						<!-- -->
					@endforeach
				</ul>

				<div class="header-nav__btn-group" style="width:200px">
{{--					@if($settings['header_btn'])--}}
{{--						@if($user)--}}
{{--							<a href="/account" class="btn-header">Особистий кабінет</a>--}}
{{--						@else--}}
{{--							<button type="button" class="btn-logIn" data-toggle="modal" data-target="#log__in-modal">Вхід</button>--}}
{{--							<button type="button" class="btn-reg btn-red" data-toggle="modal" data-target="#create-modal">Реєстрація</button>--}}
{{--						@endif--}}
{{--					@endif--}}
				</div>

				<div class="burger-menu">
					<span class="menu__icon"></span>
					<div class="menu__body">
						<a href="/" class="header-nav__logo">
							<img src="/img/logo.png" alt="Veolia">
						</a>

						<ul class="menu-nav__list">
							@foreach($menu as $item)
								<!-- -->
								<li class="menu-nav__list__item">
									<a href="{{url($item->url)}}">{{$item->title}}</a>
								</li>
								<!-- -->
							@endforeach

{{--							<div class="menu-nav__btn-group">--}}
{{--								@if($settings['header_btn'])--}}
{{--									@if($user)--}}
{{--										<a href="/account" class="btn-header">Особистий кабінет</a>--}}
{{--									@else--}}
{{--										<button type="button" class="btn-logIn" data-toggle="modal" data-target="#log__in-modal">Вхід</button>--}}
{{--										<button type="button" class="btn-reg btn-red" data-toggle="modal" data-target="#create-modal">Реєстрація</button>--}}
{{--									@endif--}}
{{--								@endif--}}
{{--							</div>--}}
						</ul>
					</div>
				</div>
			</nav>

			@if($data->header)
				<div class="header-banner container">
					<h1>{!!$data->header!!}</h1>

					@if($data->subheader)
						<p>{!!$data->subheader!!}</p>
					@endif

					@if($data->show_btn)
						<a href="{{$data->btn_url}}" class="{{$data->btn_class}}">{{$data->btn_label}}</a>
					@endif
				</div>
			@endif
		</header>

		@yield('content')

		@if($page['uri'] == 'contacts')
			@include('components.contacts', ['contacts' => $contacts, 'settings' => $settings])
		@endif

		@if($settings['map_url'])
			<section class="map__wrapper">
				<div class="map">
					<iframe src="{!!$settings['map_url']!!}" width="100%" height="530px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				</div>
			</section>
		@endif

		<footer>
			<div class="footer container">
				<div class="link__group">
					@foreach($footer_menu as $item)
						<!-- -->
						<a href="{{url($item->url)}}">{{$item->title}}</a>
						<!-- -->
					@endforeach
				</div>

				<span>{{$settings['copyright']}}</span>
			</div>
		</footer>

		@include('modals.login'			, ['user' => $user])
		@include('modals.registration'	, ['user' => $user])
		@include('modals.activation'	, ['user' => $user])
		@include('modals.recovery'		, ['user' => $user])
		@include('modals.new-password'	, ['user' => $user, 'code' => $code])
		@include('modals.calc'			, ['tariff_category' => $tariff_category, 'calc_object' => $calc_object])

		<div id="cookie_notification">
			<p>{{trans('site.cookie.text')}}</p>
			<button class="button cookie_accept">{{trans('site.cookie.btn')}}</button>
		</div>

		<div class="scrollup">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M3.73324 11.2L8.31244 6.62081L12.8916 11.2L14.3999 9.69175L8.31244 3.60428L2.22497 9.69175L3.73324 11.2Z"/>
			</svg>
		</div>

		<?php
			if($styles['footer']){
				foreach($styles['footer'] as $group){
					if($group['active'] && $group['files']){
						if((isset($group['page']) && $group['page']) && $group['page'][0] != 'all'){
							if(!in_array($page['uri'], $group['page'])){
								continue;
							}
						}

						echo $use->stylesheet($group['files'], $group['dir'], $group["media"], $group['minimize']);
					}
				}
			}

			if($scripts['footer']){
				foreach($scripts['footer'] as $group){
					if($group['active'] && $group['files']){
						if((isset($group['page']) && $group['page']) && $group['page'][0] != 'all'){
							if(!in_array($page['uri'], $group['page'])){
								continue;
							}
						}

						echo $use->javascript($group['files'], $group['dir'], $group['minimize']);
					}
				}
			}
		?>

		{!!$settings['footer_code']!!}

		<script type="text/javascript">
			(function(d, w, s) {
				var widgetHash = 'f341u8imnnjgxcvi0hwr', gcw = d.createElement(s); gcw.type = 'text/javascript'; gcw.async = true;
				gcw.src = '//widgets.binotel.com/getcall/widgets/'+ widgetHash +'.js';
				var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(gcw, sn);
			})(document, window, 'script');
		</script>

        <!-- KeyCRM online chat widget begin -->
        <script type="text/javascript">
            (function(w,d,t,u,c){
                var s=d.createElement(t),j=d.getElementsByTagName(t)[0];s.src=u;s["async"]=true;s.defer=true;s.onload=function(){KeyCRM.render(c);};j.parentNode.insertBefore(s,j)
            })(window, document, "script","https://chat.key.live/bundles/widget.min.js",{token:"22dc2c1e-24ee-4d51-94d0-185904bf89a4"});
        </script>
        <!-- KeyCRM online chat widget end -->
    </body>
</html>
