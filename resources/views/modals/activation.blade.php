<div class="modal fade" id="verification-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="pass__verification-page popup">
				<div class="modal-header">
					<img src="/img/verif.svg">
					<h2 class="popup__title">Підтвердити номер телефону</h2>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="pass__verification-form">
						<input type="hidden" name="token" value="" />
						
						<fieldset class="fieldset">
							<p>На цей номер <span class="number"></span> відправлений SMS-код, введіть його в поле нижче:</p>
							
							<label class="verif-code">
								<input class="form-control verifCode" type="text" name="verifCode" autocomplete="off" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder=" "  onfocus="this.value=''"/>
								<input class="form-control verifCode" type="text" name="verifCode" autocomplete="off" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder=" "  onfocus="this.value=''"/>
								<input class="form-control verifCode" type="text" name="verifCode" autocomplete="off" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder=" "  onfocus="this.value=''"/>
								<input class="form-control verifCode" type="text" name="verifCode" autocomplete="off" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder=" "  onfocus="this.value=''"/>
								<input class="form-control verifCode" type="text" name="verifCode" autocomplete="off" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder=" "  onfocus="this.value=''"/>
							</label>
							
							<a href="#" class="sendAgain">Відправити знову <span id="seconds"></span></a>
							
							<button class="btn-submit" type="submit" name="logIn">Вхід</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
