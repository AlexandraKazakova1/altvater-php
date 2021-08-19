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
		{!!$settings['body_code']!!}
		
		<header>
			<nav class="header-nav container">
				<a href="/" class="header-nav__logo">
					<img src="/img/logo.png" alt="Veolia">
				</a>
				
				<ul class="header-nav__list">
					@foreach($menu as $item)
						<!-- -->
						<li class="header-nav__list__item">
							<a href="{{url($item->url)}}">{{$item->title}}</a>
						</li>
						<!-- -->
					@endforeach
				</ul>
				
				<div class="header-nav__btn-group">
					<a href="#" class="btn-logIn">Вхід</a>
					<a href="#" class="btn-reg btn-red">Реєстрація</a>
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
							
							<div class="menu-nav__btn-group">
								<a href="#" class="btn-logIn">Вхід</a>
								<a href="#" class="btn-reg btn-red">Реєстрація</a>
							</div>
						</ul>
					</div>
				</div>
			</nav>
			
			@if($page['uri'] == 'index')
				<div class="header-banner container">
					<h1 class="header-banner__title">ТОВ "Альтфатер Київ"<br>представляє собою сучасну компанію</h1>
					
					<div class="header-banner__descrption">Ваш надійний партнер у сфері поводження з відходами!</div>
					
					<a href="#" class="header-banner__link btn-red">Отримати консультацію</a>
				</div>
			@endif
		</header>
		
		@yield('content')
		
		<footer>
			<div class="footer container">
				<div class="link__group">
					<a href="#">Terms & Conditions</a>
					<a href="#">Privacy Policy</a>
				</div>
				
				<span>Copyright © 2021 Veolia. All rights reserved</span>
			</div>
		</footer>
		
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
	</body>
</html>
