@extends('layout')

@section('content')
	<main>
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
	</main>
@stop
