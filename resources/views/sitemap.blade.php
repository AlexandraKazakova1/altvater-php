@extends('layout')

@section('content')
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
