@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Головна</a></li>
					<li class="breadcrumbs__item active">
						<div class="breadcrumbs__toggle">
							<span>Контакти</span>
							
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
				</ul>
			</div>
		</section>
	</main>
@stop
