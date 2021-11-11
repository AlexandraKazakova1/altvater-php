@extends('account.layout')

@section('main')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/account">Кабінет</a></li>
					<li class="breadcrumbs__item active">
						<span>Мої замовлення</span>
					</li>
				</ul>
			</div>
		</section>
		
		<h1 class="page__title">Мої замовлення</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="newOrder">
			<h2>Створити замовлення</h2>
			
			<form class="newOrder__form" id="newOrder-form">
				<fieldset class="fieldset">
					<label>
						<span class="input-description">Оберіть послугу:</span>
						
						<select class="custom-select2" name="service">
							<option data-id="0" class="first-option" disabled selected value hidden></option>
							@foreach($services as $item)
							<option value="{{$item->id}}">{{$item->name}}</option>
							@endforeach
						</select>
						
						<span class="input-placeholder">
							<div>
								<img src="/img/cabinet/modal-icon/order.svg">
							</div>
							Обрати послугу
						</span>
					</label>
					
					<label>
						<span class="input-description">Оберіть дату:</span>
						<input class="form-control datapicker" type="date" name="date" placeholder=" ">
					</label>
					
					<label>
						<span class="input-description">Введіть адресу:</span>
						<input class="form-control" type="text" name="addresses" placeholder=" ">
						<span class="input-placeholder">
							<div>
								<img src="/img/cabinet/modal-icon/house.svg">
							</div>
							Адреса
						</span>
					</label>
					
					<label>
						<span class="input-description">Оберіть час:</span>
						<input class="form-control timepicker" type="text" name="time" placeholder=" ">
					</label>
					
					<label>
						<span class="input-description">Введіть коментар:</span>
						<input class="form-control" type="text" name="comment" placeholder=" ">
						<span class="input-placeholder">
							<div>
								<img src="/img/cabinet/modal-icon/messages.svg">
							</div>
							Коментар
						</span>
					</label>
					
					<div class="submit__wrap">
						<button class="btn-submit" type="submit" name="reg">Замовити</button>
					</div>
				</fieldset>
			</form>
		</section>
		
		<section class="orders__wrapper">
			<div class="selector">
				<ul>
					<li id="all" data-type="all" class="act">Всі замовлення</li>
					<li id="processed" data-type="processed" >В обробці</li>
					<li id="performed" data-type="performed" >Виконуються</li>
					<li id="ready" data-type="ready" >Готові</li>
					<li id="planned" data-type="planned" >Заплановано</li>
				</ul>
			</div>
			
			<div class="orders__content">
				<div class="filters">
					<div>
						Список замовлень: <span class="counter" id="count_orders">{{$count}}</span>
					</div>
				</div>
				
				<div class="orders__list" id="orders__list">
					@include('account.components.orders', ['data' => $data])
				</div>
			</div>
		</section>
	</main>
@stop
