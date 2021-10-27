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
					<div class="radio__select">
						<button class="btn-individual act">
							<span>Фізична особа</span>
						</button>
						
						<button class="btn-entity">
							<span>Юридична особа</span>
						</button>
					</div>
					
					<form class="popup__form act" id="create-form__individual">
						<fieldset class="fieldset">
							<label>
								<span class="input-description">Ім'я і Прізвище:</span>
								<input class="form-control" type="text" name="name" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/profile.svg">
									</div>
									Ім'я і Прізвище
								</span>
							</label>
							
							<label>
								<span class="input-description">Номер телефону:</span>
								<input class="form-control" type="tel" name="phone" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/phone.svg">
									</div>
									Номер телефону
								</span>
							</label>
							
							<label>
								<span class="input-description">Email:</span>
								<input class="form-control" type="email" name="email" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/email.svg">
									</div>
									Ваш Email
								</span>
							</label>
							
							<label>
								<span class="input-description">Пароль:</span>
								<input class="form-control password" type="password"  name="password" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/lock.svg">
									</div>
									Введіть пароль
								</span>
							</label>
							
							<label>
								<span class="input-description">Повторіть пароль:</span>
								<input class="form-control" type="password"  name="confirm_password" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/lock.svg">
									</div>
									Повторіть пароль
								</span>
							</label>
							
							<div class="user-agree">
								<label class="checkbox__label">
									<input class="input-checkbox" type="checkbox" name="agree">
									<span class="custom-checkbox"></span>
									<span class="checkbox-text">Я даю згоду на обробку <a href="/terms" target="_blank">особистих даних</a></span>
								</label>
							</div>
							
							<div class="recaptcha" style="display:none;">
								<img src="/img/cabinet/zaglushka-3.png" alt="">
							</div>
							
							<button class="btn-submit" type="submit" name="reg">Створити акаунт</button>
						</fieldset>
					</form>
					
					<form class="popup__form" id="create-form__entity">
						<fieldset class="fieldset">
							<label>
								<span class="input-description">Назва компанії:</span>
								<input class="form-control" type="text" name="userName" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/profile.svg">
									</div>
									Назва компанії
								</span>
							</label>
							
							<label>
								<span class="input-description">Контактна особа:</span>
								<input class="form-control" type="text" name="userContactName" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/profile.svg">
									</div>
									Контактна особа
								</span>
							</label>
							
							<label>
								<span class="input-description">Юридична адреса:</span>
								<input class="form-control" type="text" name="userEntityAddress" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/location.svg">
									</div>
									Юридична адреса
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
								<span class="input-description nr">Додатковий телефон:</span>
								<input class="form-control" type="tel" name="userAddTel" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/phone.svg">
									</div>
									Додатковий телефон
								</span>
							</label>
							
							<label>
								<span class="input-description">ІПН:</span>
								<input class="form-control" type="text" name="ipn" placeholder=" ">
								<span class="input-placeholder">
									ІПН
								</span>
							</label>
							
							<label>
								<span class="input-description">ЄДРПОУ:</span>
								<input class="form-control" type="text" name="uedrpou" placeholder=" ">
								<span class="input-placeholder">
									ЄДРПОУ
								</span>
							</label>
							
							<label>
								<span class="input-description">Поштовий індекс:</span>
								<input class="form-control" type="text" name="postIndex" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/location.svg">
									</div>
									Поштовий індекс
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
							
							<div class="recaptcha" style="display:none;">
								<img src="/img/cabinet/zaglushka-3.png" alt="">
							</div>
							
							<button class="btn-submit" type="submit" name="reg">Створити акаунт</button>
						</fieldset>
					</form>
					
					<span class="popup__question">У вас є акаунт? <button type="button" class="popup-text__link btn-logIn" data-toggle="modal" data-target="#log__in-modal">Зайти в особистий кабінет</button></span>
					
					<div class="or__text" style="display:none;">
						<span>або продовжити</span>
					</div>
					
					<div class="log__with" style="display:none;">
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
