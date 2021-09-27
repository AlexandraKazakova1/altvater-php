@extends('layout')

@section('content')
	<main>
		@if(count($services))
			<section class="services-wrapper" id="our-services">
				<div class="services container">
					<h2 class="section-title">Наші послуги</h2>
					
					<div class="services__list">
						@foreach($services as $item)
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
		@endif
		
		<section class="about-wrapper">
			<div class="about container">
				<h2 class="section-title">{!!$data->about_header!!}</h2>
				
				<div class="about-content">
					@if($data->about_left || $data->about_right)
						<div class="description-group">
							@if($data->about_left)
								<p class="text">{!!$data->about_left!!}</p>
							@endif
							@if($data->about_right)
								<p class="text">{!!$data->about_right!!}</p>
							@endif
						</div>
					@endif
					
					@if($data->text)
						<div class="description-group">
							<p class="text">{!!$data->text!!}</p>
						</div>
					@endif
					
					@if($data->meta_public)
						<div class="goal">
							@if($data->meta_header)
								<h3 class="goal__title subTitle21">{!!$data->meta_header!!}</h3>
							@endif
							@if($data->meta_text)
								<p class="goal__text">{!!$data->meta_text!!}</p>
							@endif
						</div>
					@endif
					
					@if($data->meta_image)
						<div class="our-photo"><img src="/storage/{{$data->meta_image}}" alt="{!!$data->meta_header!!}"></div>
					@endif
				</div>
			</div>
		</section>
		
		@if(count($news))
			<section class="news-wrapper">
				<div class="news container">
					<h2 class="section-title">Новини нашої компанії</h2>
					
					<ul class="news__list">
						@foreach($news as $item)
							<!-- -->
							<li class="news__item">
								<a class="news__photo-link" style="width:100%;max-height:208px;overflow:hidden;" href="/news/{{$item->slug}}"><img style="width:100%;" class="news__photo" src="/storage/{{$item->image}}" alt="{!!$item->title!!}"></a>
								
								<div class="news__text">
									<span class="news__date">{{$item->date->d}} {{trans('site.months')[$item->date->m]}}, {{$item->date->y}}</span>
									<span>{!!$item->title!!}</span>
								</div>
								
								<a class="news__link" href="/news/{{$item->slug}}">
									<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									
									<span>Детальніше</span>
								</a>
							</li>
							<!-- -->
						@endforeach
					</ul>
				</div>
			</section>
		@endif
		
		@if(count($reviews))
			<section class="response-wrapper">
				<div class="response container">
					<h2 class="section-title">Відгуки наших клієнтів</h2>
					
					<div class="response__slider slider">
						@foreach($reviews as $item)
							<!-- -->
							<div class="slider__item">
								<div class="response__photo"><img src="/storage/{{$item->image}}" alt="{{strip_tags($item->name)}}"></div>
								
								<div class="response__body">
									<div class="quotes">
										<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
											<g opacity="0.2" clip-path="url(#clip0)">
												<path d="M0 32V59.4286H27.4286V32H9.14292C9.14292 21.9175 17.3461 13.7143 27.4286 13.7143V4.57142C12.3036 4.57142 0 16.875 0 32Z" fill="#7ABCCE"/>
												<path d="M64.0009 13.7143V4.57142C48.8758 4.57142 36.5723 16.875 36.5723 32V59.4286H64.0009V32H45.7152C45.7152 21.9175 53.9184 13.7143 64.0009 13.7143Z" fill="#7ABCCE"/>
											</g>
										</svg>
									</div>
									<p class="response__text">{!!$item->text!!}</p>
									<span class="responser__name">{!!$item->name!!}</span>
								</div>
							</div>
							<!-- -->
						@endforeach
					</div>
				</div>
			</section>
		@endif
		
		@if($detail && $detail->text)
			<section class="detail__wrapper">
				<div class="detail container">
					<h2 class="section-title">{{$detail->header}}</h2>
					
					<div class="detail__text">
						{!!$detail->text!!}
					</div>
				</div>
			</section>
		@endif
		
		@if(count($faq))
			<section class="faq__wrapper">
				<div class="faq container">
					<h2 class="section-title">Відповіді на запитання</h2>
					
					<ul class="faq__question__list">
						@foreach($faq as $item)
							<!-- -->
							<li class="faq__question">
								<span class="question">
									{!!$item->title!!}
									<span class="arrow">
										<svg width="21" height="13" viewBox="0 0 21 13" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.5007 11.0832L9.79354 11.7903C10.1841 12.1808 10.8172 12.1808 11.2078 11.7903L10.5007 11.0832ZM20.3744 2.62361C20.7649 2.23309 20.7649 1.59992 20.3744 1.2094C19.9839 0.818873 19.3507 0.818873 18.9602 1.2094L20.3744 2.62361ZM0.626878 2.62361L9.79354 11.7903L11.2078 10.3761L2.04109 1.2094L0.626878 2.62361ZM11.2078 11.7903L20.3744 2.62361L18.9602 1.2094L9.79354 10.3761L11.2078 11.7903Z"/>
										</svg>
									</span>
								</span>
								<span class="reply">{!!$item->text!!}</span>
							</li>
							<!-- -->
						@endforeach
					</ul>
				</div>
			</section>
		@endif
		
		@if($settings['map_url'])
			<section class="map__wrapper">
				<div class="map">
					<iframe src="{!!$settings['map_url']!!}" width="100%" height="530px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				</div>
			</section>
		@endif
	</main>
@stop
