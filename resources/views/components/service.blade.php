<section class="orderService__wrapper" style="display:none;">
	<form class="orderService__form conteiner">
		<h2 class="popup__title">Замовити послугу</h2
		
		<fieldset>
			<label>
				<span class="input-description">Оберіть послугу:</span>
				<select class="custom-select2" name="service">
					<option data-id="0" class="first-option" disabled selected value hidden>Обрати послугу</option>
					<option value="Послуга-1">Послуга-1</option>
					<option value="Послуга-2">Послуга-2</option>
					<option value="Послуга-3">Послуга-3</option>
				</select>
			</label>
			
			<label>
				<span>Ім'я </span>
				<input class="form-control" type="text" id="username" name="name" placeholder="Ваше Ім'я">
			</label>
			
			<label>
				<span>Телефон</span>
				<input class="form-control" type="tel" id="userphone" name="phone" placeholder="Ваш телефон">
			</label>
			
			<label>
				<span>Повідомлення</span>
				<textarea placeholder="Ваше Повідомлення" id="massage" name="massage"></textarea>
			</label>
			
			<button class="btn-submit" type="submit" name="submit">Залишити заявку</button>
			
			<label class="checkbox__label">
				<input class="input-checkbox" type="checkbox" id="rule" name="rule">
				<span class="custom-checkbox"></span>
				<span class="checkbox-text">Підтверджуючи замовлення, я приймаю умови угоди користувача</span>
			</label>
		</fieldset>
	</form>
</section>
