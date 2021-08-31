@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Головна</a></li>
					
					<li class="breadcrumbs__item active">
						<span>Наші послуги</span>
						
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
		
		<section class="services-wrapper">
			<div class="services container">
				<div class="services__list">
					@foreach($all_services as $item)
						<!-- -->
						<div class="services__item">
							<a class="services__photo-link" href="/services/{{$item->slug}}"><img class="services__photo" src="/storage/{{$item->image}}" alt="{{$item->title}}"></a>
							
							<span class="services__text">{!!$item->title!!}</span>
							
							<a class="services__link" href="/services/{{$item->slug}}">
								<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M23.8643 14.1685L5.73926 14.1685" stroke="white" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="white" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<span>Детальніше</span>
							</a>
						</div>
						<!-- -->
					@endforeach
				</div>
			</div>
		</section>
		
		@if($data->text)
			<section class="detail__wrapper">
				<div class="detail container">
					<div class="detail__text">
						{!!$data->text!!}
					</div>
				</div>
			</section>
		@endif
	</main>
@stop
