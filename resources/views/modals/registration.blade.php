<div class="modal fade" id="create-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="create-page popup">
				<div class="modal-header">
					<h2 class="popup__title">Створити Акаунт</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="create-form">
						<fieldset class="fieldset">
							<div class="radio__select">
								<label>
									<input type="radio" name="user-type" id="individual" checked>
									<span>Фізична особа</span>
								</label>
								
								<label>
									<input type="radio" name="user-type" id="legal">
									<span>Юридична особа</span>
								</label>
							</div>
							
							<label>
								<span class="input-description">Ім'я і Прізвище:</span>
								<input class="form-control" type="text" name="userName" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/profile.svg">
									</div>
									Ім'я і Прізвище
								</span>
							</label>
							
							<label>
								<span class="input-description">Номер телефону:</span>
								<input class="form-control" type="tel" name="userTel" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/phone.svg">
									</div>
									Номер телефону
								</span>
							</label>
							
							<label>
								<span class="input-description">Email:</span>
								<input class="form-control" type="email" name="userEmail" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/email.svg">
									</div>
									Ваш Email
								</span>
							</label>
							
							<label>
								<span class="input-description">Пароль:</span>
								<input class="form-control password" type="password"  name="userPassword" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/lock.svg">
									</div>
									Введіть пароль
								</span>
							</label>
							
							<label>
								<span class="input-description">Повторіть пароль:</span>
								<input class="form-control" type="password"  name="userPasswordConfirm" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/lock.svg">
									</div>
									Повторіть пароль
								</span>
							</label>
							
							<div class="user-agree">
								<label class="checkbox__label">
									<input class="input-checkbox" type="checkbox" name="userAgree">
									<span class="custom-checkbox"></span>
									<span class="checkbox-text">Я даю згоду на обробку <a href="#">особистих даних</a></span>
								</label>
							</div>
							
							<div class="recaptcha">
								<img src="/img/cabinet/zaglushka-3.png" alt="">
							</div>
							
							<button class="btn-submit" type="submit" name="reg">Створити акаунт</button>
						</fieldset>
					</form>
					
					<span class="popup__question">У вас є акаунт? <button type="button" class="popup-text__link btn-logIn" data-toggle="modal" data-target="#log__in-modal">Зайти в особистий кабінет</button></span>
					
					<div class="or__text">
						<span>або продовжити</span>
					</div>
					
					<div class="log__with">
						<a href="/social/google">
							<img src="/img/cabinet/modal-icon/google.svg" alt="G">
						</a>
						<a href="/social/facebook">
							<img src="/img/cabinet/modal-icon/facebook.svg" alt="f">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
