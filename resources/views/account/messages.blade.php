@extends('account.layout')

@section('main')
	<main class="request__page">
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
		
		<h1 class="page__title">Повідомлення</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="request">
			<form class="request__form" id="request__form" action="POST">
				<fieldset>
					<div>
						<label>
							<span class="input-description">Вибір тематики заявки:</span>
							<input class="form-control" type="text" name="theme" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/text.svg">
								</div>
								Тематика заявки
							</span>
						</label>
						
						<label>
							<span class="input-description">Номер договору:</span>
							<input class="form-control" type="number" name="number" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/documents.svg">
								</div>
								Номер договору
							</span>
						</label>
						
						<label>
							<span class="input-description">Телефон:</span>
							<input class="form-control" type="tel" name="phone" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/phone.svg">
								</div>
								Ваш телефон
							</span>
						</label>
					</div>
					
					<div>
						<label>
							<span class="input-description">Заголовку звернення:</span>
							<input class="form-control" type="text" name="header" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/text.svg">
								</div>
								Заголовок
							</span>
						</label>
						
						<label>
							<span class="input-description">Коментар</span>
							<textarea placeholder=" " name="text"></textarea>
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/messages.svg">
								</div>
								Коментар
							</span>
						</label>
					</div>
					
					<div class="submit__wrap">
						<button class="btn-submit" type="submit" name="add-request">Залишити заявку</button>
					</div>
				</fieldset>
			</form>
		</section>
	</main>
@stop
