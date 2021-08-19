@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Veolia</a></li>
					<li class="breadcrumbs__item">
						<span>Новини</span>
						
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
					
					<li class="active breadcrumbs__item">
						<span>Компанія Veolia Water Technologies, провідний постачальник рішень для очищення води та стічних вод, протягом останніх шести місяців підписала нові стратегії.</span>
						
						<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						
						<ul class="dropdown__list">
							<!-- -->
							<li class="dropdown__link">
								<a href="#">
									Послуги
								</a>
							</li>
							<!-- -->
						</ul>
					</li>
				</ul>
			</div>
		</section>
		
		<section  class="open-news__wrapper container">
			<div class="open-news">
				{!!$data->text!!}
				
				<div class="share">
					<a class="share__facebook" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" target="_blank">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10.0001 0.041748C4.47724 0.041748 0 4.51898 0 10.0418C0 14.9951 3.60522 19.0973 8.33225 19.8917V12.1281H5.91994V9.33429H8.33225V7.27425C8.33225 4.88402 9.79213 3.58147 11.9247 3.58147C12.9461 3.58147 13.8238 3.65758 14.0786 3.6911V6.18954L12.5995 6.19025C11.44 6.19025 11.2165 6.74114 11.2165 7.54982V9.33286H13.9832L13.6223 12.1267H11.2165V19.9585C16.1642 19.3563 20 15.1496 20 10.0389C20 4.51898 15.5228 0.041748 10.0001 0.041748Z" fill="#57585A"/>
						</svg>
						<span>Поділитись в Facebook</span>
					</a>
					<a href="#">
						<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M22 0.0100873C21.0424 0.687685 19.9821 1.20594 18.86 1.54489C18.2577 0.850227 17.4573 0.357867 16.567 0.134402C15.6767 -0.0890626 14.7395 -0.0328515 13.8821 0.295433C13.0247 0.623718 12.2884 1.20824 11.773 1.96994C11.2575 2.73163 10.9877 3.63376 11 4.55431V5.55744C9.24263 5.60316 7.50127 5.21218 5.93101 4.41933C4.36074 3.62648 3.01032 2.45638 2 1.01323C2 1.01323 -2 10.0415 7 14.054C4.94053 15.4564 2.48716 16.1595 0 16.0603C9 21.076 20 16.0603 20 4.52421C19.9991 4.24479 19.9723 3.96606 19.92 3.69161C20.9406 2.68194 21.6608 1.40717 22 0.0100873Z" fill="#57585A"/>
						</svg>
						<span>Поділитись в Twitter</span>
					</a>
				</div>
			</div>
			
			<aside class="last-news">
				<h3 class="subTitle21">Останні новини</h3>
				
				<ul class="last-news__list">
					@if(count($news))
						@foreach($news as $item)
							<!-- -->
							<li class="last-news__item">
								<a href="/news/{{$item->slug}}">
									<span class="news__title">{!!$item->title!!}</span>
									<div class="pubdate">
										<span>{{$item->date->d}} {{trans('site.months')[$item->date->m]}}</span>                        
									</div>
								</a>
							</li>
							<!-- -->
						@endforeach
					@endif
				</ul>
			</aside>
		</section>
	</main>
@stop
