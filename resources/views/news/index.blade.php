@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Veolia</a></li>
					
					<li class="breadcrumbs__item active">
						<span>Новини</span>
						
						<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						
						<ul class="dropdown__list">
							@foreach($pages as $item)
								<!-- -->
								<li class="dropdown__link">
									<a href="{{url($item->slug == 'index' ? '/' : $item->slug)}}">{{$item->title}}</a>
								</li>
								<!-- -->
							@endforeach
						</ul>
					</li>
				</ul>
			</div>
		</section>
		
		<section class="topNews-wrapper">
			<div class="topNews container">
				@if($last)
					<a class="topNews__link" href="/news/{{$last->slug}}" style="background:url(/storage/{{$last->image}}) center no-repeat;background-size: cover;">
						<div class="link__body">
							<h2 class="link__title">{{$last->title}}</h2>
							
							<div class="pubdate">
								<span>{{$last->date->d}} {{trans('site.months')[$last->date->m]}}</span>                        
							</div>
						</div>
					</a>
				@endif
				
				<ul class="popular__news__list">
					<h3 class="subTitle21">Популярні новини</h3>
					
					@if(count($popular))
						@foreach($popular as $item)
							<!-- -->
							<li class="popular__news__item">
								<a href="/news/{{$item->slug}}">
									<span class="news__title">{{$item->title}}</span>
									
									<div class="pubdate">
										<span>{{$last->date->d}} {{trans('site.months')[$last->date->m]}}</span>                        
									</div>
								</a>
							</li>
							<!-- -->
						@endforeach
					@endif
				</ul>
			</div>
		</section>
		
		<section class="news-wrapper">
			<div class="news container">
				<h2 class="section-title">Новини нашої компанії</h2>
				
				<ul class="news__list">
					@if(count($news))
						@foreach($news as $item)
							<!-- -->
							<li class="news__item">
								<a class="news__photo-link" style="width:100%;max-height:208px;overflow:hidden;" href="/news/{{$item->slug}}"><img style="width:100%;" class="news__photo" src="/storage/{{$item->image}}" alt="{{$item->title}}"></a>
								
								<div class="news__text">
									<span class="news__date">{{$item->date->d}} {{trans('site.months')[$item->date->m]}}, {{$item->date->y}}</span>
									<span>{!!$item->title!!}</span>
								</div>
								
								<a class="news__link" href="#">
									<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<span>Детальніше</span>
								</a>
							</li>
							<!-- -->
						@endforeach
					@endif
				</ul>
			</div>
		</section>
	</main>
@stop
