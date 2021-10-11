@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Головна</a></li>
					
					<li class="breadcrumbs__item">
						<div class="breadcrumbs__toggle">
							<span>Наші послуги</span>
							
							<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						
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
					
					<li class="breadcrumbs__item active">
						<div class="breadcrumbs__toggle">
							<span>{!!$data->header!!}</span>
							
							<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						
						<ul class="dropdown__list">
							@foreach($all_services as $item)
								<!-- -->
								<li class="dropdown__link">
									<a href="{{url('services/'.$item->slug)}}">{{$item->title}}</a>
								</li>
								<!-- -->
							@endforeach
						</ul>
					</li>
				</ul>
			</div>
		</section>
		
		<section class="osbb__wrapper">
			<div class="osbb container">
				{!!$data->text!!}
			</div>
		</section>
		
		@if(count($images))
			<section class="last-work__wrapper">
				<div class="last-work container">
					<h2 class="section-title">Останні виконані роботи:</h2>
					
					<div class="last-work__slider slider">
						@foreach($images as $item)
							<!-- -->
							<div>
								<div class="last-work__photo"><img src="/storage/{{$item->image}}" alt="{{$item->alt}}"></div>
							</div>
							<!-- -->
						@endforeach
					</div>
					
					@if($data->slider_label)
						<div class="comment">
							<svg width="91" height="75" viewBox="0 0 91 75" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path opacity="0.8" fill-rule="evenodd" clip-rule="evenodd" d="M49.8564 22.2874C49.8564 29.9788 52.5993 35.9221 58.0852 40.1174L45.5001 74.9907H66.0718L85.4335 40.1174C89.1445 33.4748 91 27.5315 91 22.2874C91 15.2952 89.0235 9.83264 85.0705 5.89954C81.1175 1.96643 76.2368 -9.15527e-05 70.4282 -9.15527e-05C64.4583 -9.15527e-05 59.5373 2.09753 55.6649 6.29285C51.7926 10.4882 49.8564 15.8196 49.8564 22.2874ZM4.35712 22.2874C4.35712 29.9788 7.09999 35.9221 12.5858 40.1174L0.000732422 74.9907H20.5725L39.9342 40.1174C43.6452 33.4748 45.5007 27.5315 45.5007 22.2874C45.5007 15.2952 43.5242 9.83264 39.5712 5.89954C35.6181 1.96643 30.7374 -9.15527e-05 24.9289 -9.15527e-05C18.959 -9.15527e-05 14.038 2.09753 10.1656 6.29285C6.29326 10.4882 4.35712 15.8196 4.35712 22.2874Z" fill="#7ABCCE"/>
							</svg>
							
							<p class="section-text">{{$data->slider_label}}</p>
						</div>
					@endif
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
	</main>
@stop
