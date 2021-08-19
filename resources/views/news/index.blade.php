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
		
		<section class="topNews-wrapper">
			<div class="topNews container">
				@if($last)
					<a class="topNews__link" href="/news/{{$last->slug}}" style="background:url(/storage/{{$last->image}}) center no-repeat;background-size: cover;">
						<div class="link__body">
							<h2 class="link__title">{{$last->title}}</h2>
							
							<div class="pubdate">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="white"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 6C12.5523 6 13 6.44772 13 7V11.5858L15.2071 13.7929C15.5976 14.1834 15.5976 14.8166 15.2071 15.2071C14.8166 15.5976 14.1834 15.5976 13.7929 15.2071L11.2929 12.7071C11.1054 12.5196 11 12.2652 11 12V7C11 6.44772 11.4477 6 12 6Z" fill="white"/>
								</svg>
								
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
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="#BFBEBE"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12 6C12.5523 6 13 6.44772 13 7V11.5858L15.2071 13.7929C15.5976 14.1834 15.5976 14.8166 15.2071 15.2071C14.8166 15.5976 14.1834 15.5976 13.7929 15.2071L11.2929 12.7071C11.1054 12.5196 11 12.2652 11 12V7C11 6.44772 11.4477 6 12 6Z" fill="#BFBEBE"/>
										</svg>
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
					<li class="news__item">
						<a class="news__photo-link" href="#"><img class="news__photo" src="../img/news1.png" alt="news"></a>
						<div class="news__text">
							<span class="news__date">14 Апреля,2021</span>
							<span>Там буде зелена зона для відпочинку..</span>
						</div>
						<a class="news__link" href="#">
							<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span>Детальніше</span>
						</a>
					</li>
					<li class="news__item">
						<a class="news__photo-link" href="#"><img class="news__photo" src="../img/news2.png" alt="news"></a>
						<div class="news__text">
							<span class="news__date">14 Апреля,2021</span>
							<span>Там буде зелена зона для відпочинку..</span>
						</div>
						<a class="news__link" href="#">
							<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span>Детальніше</span>
						</a>
					</li>
					<li class="news__item">
						<a class="news__photo-link" href="#"><img class="news__photo" src="../img/news3.png" alt="news"></a>
						<div class="news__text">
							<span class="news__date">14 Апреля,2021</span>
							<span>Там буде зелена зона для відпочинку..</span>
						</div>
						<a class="news__link" href="#">
							<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<span>Детальніше</span>
						</a>
					</li>
				</ul>
			</div>
		</section>
	</main>
@stop
