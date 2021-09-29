@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Головна</a></li>
					<li class="breadcrumbs__item active">
						<span>{{$data->title}}</span>
						
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
		
		<section class="detail__wrapper">
			<div class="detail container">
				<div class="detail__text">
					<div class="sitemap-container">
						<ul>
						@foreach($all_pages as $item)
							<li>
							@if($item->public)
								<a class="hover-color" href="{{url($item->slug)}}">{{$item->title}}</a>
							@else
								<a class="hover-color" href="" onclick="return false;">{{$item->title}}</a>
							@endif
							@if(isset($item->pages) && $item->pages)
								<ul>
								@foreach($item->pages as $sub)
									<li>
									@if($sub->public)
										<a href="{{url($item->slug.'/'.$sub->slug)}}">{{$sub->title}}</a>
									@else
										<a href="" onclick="return false;">{{$sub->title}}</a>
									@endif
									</li>
								@endforeach
								</ul>
							@endif
							</li>
						@endforeach
						</ul>
					</div>
				</div>
			</div>
		</section>
	</main>
@stop
