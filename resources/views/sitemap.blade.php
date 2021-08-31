@extends('layout')

@section('content')
	<main>
		<section class="detail__wrapper">
			<div class="detail container">
				<div class="detail__text">
					<div class="sitemap-container">
						<ul>
						@foreach($all_pages as $item)
							<li>
							@if($item->public)
								<a class="hover-color" href="{{url($item->slug)}}">{{$item->name}}</a>
							@else
								<a class="hover-color" href="" onclick="return false;">{{$item->name}}</a>
							@endif
							@if(isset($item->pages) && $item->pages)
								<ul>
								@foreach($item->pages as $sub)
									<li>
									@if($sub->public)
										<a href="{{url($item->slug.'/'.$sub->slug)}}">{{$sub->name}}</a>
									@else
										<a href="" onclick="return false;">{{$sub->name}}</a>
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
