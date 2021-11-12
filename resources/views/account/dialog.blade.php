@extends('account.layout')

@section('main')
	<main class="message__page">
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li><a href="/account">Кабінет</a></li>
					<li class="active">
						<span>Повідомлення</span>
					</li>
				</ul>
			</div>
		</section>
		
		<h1 class="page__title">{{$dialog->theme->label}}</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="message__wrapper">
			<div class="msg__field" id="messages_list">
				@foreach($messages as $item)
					@php $time = explode(' ', $item->created_at); @endphp
					<!-- -->
					<div class="{{($item->client_id ? 'user' : 'manager')}}">
						<span class="name">{{($item->client_id ? $item->client->name : $item->admin->name)}}<span class="time">{{substr($time[1], 0, 5)}}</span></span>
						<p>{{$item->text}}</p>
					</div>
					<!-- -->
				@endforeach
			</div>
			
			<div class="msg__form">
				<form id="message_form">
					<input type="hidden" name="id" value="{{$dialog->id}}" />
					<fieldset>
						<label>
							<input type="text" placeholder="Повідомлення">
						</label>
						
						<button type="submit">
							<span>Відправити</span>
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="mdi-send-circle" width="24" height="24" viewBox="0 0 24 24">
								<path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M8,7.71V11.05L15.14,12L8,12.95V16.29L18,12L8,7.71Z" />
							</svg>
						</button>
					</fieldset>
				</form>
			</div>
		</section>
	</main>
@stop
