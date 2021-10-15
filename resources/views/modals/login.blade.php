<div class="modal fade" id="log__in-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="log__in-page popup">
				<div class="modal-header">
					<h2 class="popup__title">Вхід до<br>особистого кабінету</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="log__in-form">
						<fieldset class="fieldset">
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
								<input class="form-control" type="password"  name="password" placeholder=" ">
								<span class="input-placeholder">
									<div>
										<img src="/img/cabinet/modal-icon/lock.svg">
									</div>
									Введіть пароль
								</span>
							</label>
							
							<div class="convenience">
								<label class="checkbox__label">
									<input class="input-checkbox" type="checkbox" id="remember-me" name="remember">
									<span class="custom-checkbox"></span>
									<span class="checkbox-text">Запам'ятати мене</span>
								</label>
								
								<button class="btn-forgot forgot__password__link popup-text__link" type="button" data-toggle="modal" data-target="#recovery-modal">Забули пароль?</button>
							</div>
							
							<button class="btn-submit" type="submit" name="logIn">Вхід</button>
						</fieldset>
					</form>
					
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
					
					<span class="popup__question">Немає акаунту? <button type="button" class="btn-reg popup__question__link popup-text__link" data-toggle="modal" data-target="#create-modal">Створити акаунт</button></span>
				</div>
			</div>
		</div>
	</div>
</div>
