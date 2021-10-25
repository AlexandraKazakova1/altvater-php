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
							<option value="Послуга-1">Послуга-1</option>
							<option value="Послуга-2">Послуга-2</option>
							<option value="Послуга-3">Послуга-3</option>
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
						<input class="form-control" type="text" name="date" placeholder=" ">
						<span class="input-placeholder">
							<div>
								<img src="/img/cabinet/modal-icon/date.svg">
							</div>
							Обрати дату
						</span>
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
						<input class="form-control" type="text" name="time" placeholder=" ">
						<span class="input-placeholder">
							<div>
								<img src="/img/cabinet/modal-icon/time-clock.svg">
							</div>
							Обрати час
						</span>
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
					<li class="act">Всі замовлення</li>
					<li>В обробці</li>
					<li>Виконуються</li>
					<li>Готові</li>
					<li>Заплановано</li>
				</ul>
			</div>
			
			<div class="orders__content">
				<div class="filters">
					<div>
						Список замовлень: <span class="counter">18</span>
					</div>
				</div>
				
				<div class="orders__list">
					<!-- -->
					<div class="orders__item processed">
						<span>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
							</svg>
						</span>
						
						<div class="number">
							ID Заявки:
							<span>876364</span>
						</div>
						
						<div class="service">
							Послуга:
							<span>Заміна контейнера</span>
						</div>
						
						<div class="date">
							Дата заявки:
							<span>12.12.2021</span>
						</div>
						
						<div class="status"></div>
					</div>
					<!-- -->
				</div>
			</div>
		</section>
	</main>
@stop
