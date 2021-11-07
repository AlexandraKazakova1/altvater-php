<div class="modal fade {{($code ? 'show' : '')}}" id="recovery-modal-3" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="pass__recovery-page popup">
				<div class="modal-header">
					<h2 class="popup__title">Введіть новий пароль</h2>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="pass__recovery-form3">
						<input type="hidden" name="token" value="{{$code}}" >
						
						<fieldset class="fieldset">
							<label>
								<span class="input-description">Пароль:</span>
								<input class="form-control password-2" type="password"  name="password" placeholder=" ">
								
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
							
							<button class="btn-submit" type="submit" name="logIn">Зберегти</button>
						</fieldset>
						
						<p class="responseMsg"></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
