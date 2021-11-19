<section class="orderService__wrapper">
	<form class="orderService__form">
		<h2 class="popup__title">Замовити послугу</h2>
		
		<fieldset class="callback-fieldset">
			<label>
				<span class="input-description">Оберіть послугу:</span>
				<select class="custom-select2" name="service" disabled>
					<option value="{{$data->id}}">{{$data->title}}</option>
				</select>
			</label>

			<input type="hidden" name="service_id" value="{{$data->id}}" />
			
			<label>
				<span>Телефон</span>
				<input class="form-control" type="tel" id="userphone" name="phone" placeholder="Ваш телефон">
			</label>
			
			<label>
				<span>Ім'я </span>
				<input class="form-control" type="text" id="username" name="name" placeholder="ПІБ">
			</label>
			
			<label>
				<span>Повідомлення</span>
				<textarea placeholder="Ваше повідомлення" id="massage" name="massage"></textarea>
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
