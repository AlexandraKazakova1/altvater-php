<div class="modal fade" id="recovery-modal-1" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="pass__recovery-page popup">
				<div class="modal-header">
					<h2 class="popup__title">Відновити пароль</h2>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="pass__recovery-form">
						<fieldset class="fieldset">
							<p>Укажіть електронну адресу, на яку був створений акаунт.</p>
							
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
							
							<button class="btn-submit recovery-1" type="submit" name="logIn" name="step-1" data-toggle="modal" data-target="#recovery-modal-2">Відновити пароль</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="recovery-modal-2" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="pass__recovery-page popup">
				<div class="modal-header">
					<img src="/img/recovery-mail.svg">
					<h2 class="popup__title">Перевірте пошту</h2>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<p class="recovery-mail-msg">На цей E-mail <span class="recovery-mail"></span> буде відправлено лист з подальшими інструкціями по відновленню пароля</p>
				</div>
			</div>
		</div>
	</div>
</div>
