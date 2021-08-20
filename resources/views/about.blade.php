@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Veolia</a></li>
					<li class="breadcrumbs__item active">
						<span>Про компанію</span>
						
						<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						
						<ul class="dropdown__list">
							<!-- -->
							<li class="dropdown__link">
								<a href="#">Послуги</a>
							</li>
							<!-- -->
						</ul>
					</li>
				</ul>
			</div>
		</section>
		
		@if($data->about_right_public)
			<section class="thesis__wrapper">
				<div class="thesis container">
					<div class="thesis__img"><img src="/storage/{{$data->about_right_image}}" alt="{{$data->about_right_header}}"></div>
					<div class="thesis__body">
						<h2 class="section-title">{{$data->about_right_header}}</h2>
						{!!$data->about_right!!}
					</div>
				</div>
			</section>
		@endif
		
		@if($data->about_left_public)
			<section class="thesis__wrapper">
				<div class="thesis thesis-even container">
					<div class="thesis__img"><img src="/storage/{{$data->about_left_image}}" alt="{{$data->about_left_header}}"></div>
					<div class="thesis__body">
						<h2 class="section-title">{{$data->about_left_header}}</h2>
						{!!$data->about_left!!}
					</div>
				</div>
			</section>
		@endif
		
		@if($data->indicators_public)
			<section class="stat__wrapper">
				<div class="stat container">
					<ul class="stat__list">
						<li class="stat__item">
							<span class="counter">{{$data->branches}}</span>
							<span class="type">Філій в світі</span>
						</li>
						<li class="stat__item">
							<span class="counter">{{$data->orders}}</span>
							<span class="type">Замовлень щодня</span>
						</li>
						<li class="stat__item">
							<span class="counter">{{$data->employees}}</span>
							<span class="type">Співробітників</span>
						</li>
						<li class="stat__item">
							<span class="counter">{{$data->hours}}</span>
							<span class="type">Годин працюємо</span>
						</li>
					</ul>
					
					{!!$data->indicators_text!!}
				</div>
			</section>
		@endif
		
		@if($data->meta_public)
			<section class="thesis__wrapper wrapper__gray">
				<div class="thesis thesis-even container">
					<div class="thesis__img"><img src="/storage/{{$data->meta_image}}" alt="{{$data->meta_header}}"></div>
					
					<div class="thesis__body">
						<h2 class="section-title">{!!$data->meta_header!!}</h2>
						{!!$data->meta_text!!}
					</div>
				</div>
			</section>
		@endif
		
		@if($detail && $detail->text)
			<section class="detail__wrapper" style="background: #ffffff;">
				<div class="detail container">
					<h2 class="section-title">{{$detail->header}}</h2>
					
					<div class="detail__text">
						{!!$detail->text!!}
					</div>
				</div>
			</section>
		@endif
	</main>
@stop
