@extends('layout')

@section('content')
	<div class="header-banner container">
		<h1 class="header-banner__title">{!!$data->header!!}</h1>
	</div>
	
	<main>
		<section class="detail__wrapper">
			<div class="detail container">
				<div class="detail__text">
					{!!$data->text!!}
				</div>
			</div>
		</section>
	</main>
@stop
