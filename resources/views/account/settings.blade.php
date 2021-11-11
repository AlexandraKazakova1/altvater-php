@extends('account.layout')

@section('main')
	<main class="settings__page">
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/account">Кабінет</a></li>
					<li class="breadcrumbs__item active">
						<span>Налаштування</span>
					</li>
				</ul>
			</div>
		</section>
		
		<h1 class="page__title">Налаштування</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="settings__wrapper">
			<div class="selector">
				<ul>
					<li class="act" id="general">Загальна інформація</li>
					<li id="security">Безпека</li>
				</ul>
			</div>
			
			<div class="general__content">
				<form class="settings__form" id="settingsForm">
					<fieldset>
						<label>
							<span class="input-description notCheck">Ім'я:</span>
							<input class="form-control" type="text" name="name" value="{{$user['name']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/profile.svg">
								</div>
								Ваше Ім'я
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Прізвище:</span>
							<input class="form-control" type="text" name="surname" value="{{$user['surname']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/users.svg">
								</div>
								Ваше Прізвище
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">По батькові:</span>
							<input class="form-control" type="text" name="middlename" value="{{$user['middlename']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/users.svg">
								</div>
								По батькові
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Email:<b> ?</b></span>
							<input class="form-control" type="email" name="email" readonly value="{{$user['email']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/email.svg">
								</div>
								Ваш Email
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Телефон:<b> ?</b></span>
							<input class="form-control" type="tel" name="phone" readonly value="{{$user['phone']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/phone.svg">
								</div>
								Ваш телефон
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Додатковий телефон:</span>
							<input class="form-control" type="tel" name="extra_phone" value="{{$user['extra_phone']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/phone-2.svg">
								</div>
								Ваш телефон
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Адреса:</span>
							<input class="form-control" type="text" name="address" value="{{$user['addresses']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/location.svg">
								</div>
								Адреса
							</span>
						</label>
						
						<label>
							<span class="input-description notCheck">Індекс:</span>
							<input class="form-control" type="text" name="index" value="{{$user['index']}}" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/postIndex.svg">
								</div>
								Поштовий індекс
							</span>
						</label>
						
						<div class="submit__wrap">
							<button class="btn-submit" type="submit" name="add-request">Зберегти</button>
						</div>
					</fieldset>
					<p class="responseMsg"></p>
				</form>
			</div>
			
			<div class="security__content">
				<form action="POST" id="changePasswordForm">
					<fieldset>
						<label>
							<span class="input-description">Новий пароль:</span>
							<input class="form-control" type="password" name="new_password" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/lock.svg">
								</div>
								Введіть новий пароль
							</span>
						</label>
						
						<label>
							<span class="input-description">Старий пароль:</span>
							<input class="form-control" type="password" name="password" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/lock.svg">
								</div>
								Введіть пароль
							</span>
						</label>
						
						<label>
							<span class="input-description">Повторіть новий пароль:</span>
							<input class="form-control" type="password" name="confirm_password" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/modal-icon/lock.svg">
								</div>
								Повторіть новий пароль
							</span>
						</label>
						
						<div class="submit__wrap">
							<button class="btn-submit" type="submit" name="add-request">Зберегти</button>
						</div>
					</fieldset>
					<p class="responseMsg"></p>
				</form>
			</div>
		</section>
	</main>
@stop
