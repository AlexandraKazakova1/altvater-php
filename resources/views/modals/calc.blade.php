<div class="modal fade" id="servicesCalc" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="servicesCalc-page popup">
				<div class="modal-header">
					<h2 class="popup__title">Для розрахунку заповніть поля</h2>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<img src="/img/close.svg" alt="X">
					</button>
				</div>
				
				<div class="modal-body">
					<form class="popup__form" id="servicesCalc-form">
						<fieldset class="fieldset">
							<Label class="select">
								<span class="input-description">Об'єкт утворення ТПВ</span>
								<select class="select2" name="type">
									<option data-label="Кількість людей" value="2.15">Багатоквартирні будинки</option>
									<option data-label="Кількість людей" value="2.52">Будинки приватного сектору </option>
									<option data-label="Кількість людей" value="3.25">Готелі з ресторанами, конференц-залами;</option>
									<option data-label="Кількість місць" value="1.64">Готелі: без ресторанів, конференц-залів</option>
									<option data-label="Кількість місць" value="1.39">Гуртожитки</option>
									<option data-label="Кількість місць" value="2.08">Санаторії, пансіонати, будинки відпочинку</option>
									<option data-label="Кількість місць" value="2.12">Лікарні</option>
									<option data-label="Кількість відвідувань" value="0.23">Поліклініки</option>
									<option data-label="м² площі" value="0.5">Склади</option>
									<option data-label="Кількість робочих місць" value="0.7">Адміністративні установи</option>
									<option data-label="Кількість робочих місць" value="0.95">Науково-дослідні організації</option>
									<option data-label="Кількість учнів" value="0.23">Школи, ліцеї</option>
									<option data-label="Кількість учнів" value="0.24">Вищі і середні навчальні заклади</option>
									<option data-label="Кількість учнів" value="0.95">Школи-інтернати</option>
									<option data-label="Кількість учнів" value="0.88">Профтехучилища</option>
									<option data-label="м² торговельної площі" value="0.39">Промтоварні крамниці</option>
									<option data-label="м² торговельної площі" value="0.57">Продовольчі крамниці</option>
									<option data-label="м² торговельної площі" value="0.44">Ринки (продовольчі, речові, змішані)</option>
									<option data-label="м² торговельної площі" value="0.55">Супер, гіпер-, мегамаркети</option>
									<option data-label="Кількість місць" value="0.36">Видовищні установи (стадіони, літні площадки, оглядові майданчики тощо)</option>
									<option data-label="Кількість робочих місць" value="1.26">Підприємства побутового обслуговування</option>
									<option data-label="Кількість місць" value="0.29">Заклади культури і мистецтв</option>
									<option data-label="м² площі для пасажирів" value="0.77">Залізничні вокзали, аеропорти, автовокзали</option>
									<option data-label="м² площі" value="0.07">Кемпінги, автостоянки</option>
									<option data-label="м² території" value="0.1095">Пляжі (в літній сезон)</option>
									<option data-label="Кількість місць" value="2.92">Ресторани</option>
									<option data-label="Кількість місць" value="2.19">Кафе, їдальні</option>
									<option data-label="Кількість місць" value="1.46">Відкриті сезонні торгові площадки, павільйони</option>
								</select>
							</label>
							
							<Label>
								<span class="input-description">Кількість людей</span>
								<input class="form-control" type="text" name="count" autocomplete="off" oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder=" "/>
							</Label>
							
							<Label class="select">
								<span class="input-description">Категорія тарифу</span>
								<select class="select2" name="category">
									<option value="people">населення</option>
									<option value="commerce">бюджетні установи</option>
									<option value="people">інші</option>
								</select>
							</Label>
							
							<Label class="select">
								<span class="input-description">Об'єм контейнера, м<sup>3</sup></span>
								<select class="select2" name="volume">
									<option value="0.12">0,12</option>
									<option value="0.24">0,24</option>
									<option value="0.36">0,36</option>
									<option value="0.66">0,66</option>
									<option value="0.77">0,77</option>
									<option value="1.1">1,1</option>
								</select>
							</Label>
							
							<Label class="select">
								<span class="input-description">Бажана періодичність вивезень, разів на тиждень</span>
								<select class="select2" name="period">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
								</select>
							</Label>
							
							<Label class="readonly">
								<span class="input-description">Норма, м<sup>3</sup>/рік на одиницю розрахунку</span>
								<input class="form-control" type="text" readonly />
							</Label>
							
							<Label class="readonly">
								<span class="input-description">&#8776; тонн/рік (для декларації, від 50т)</span>
								<input class="form-control" type="text" readonly />
							</Label>
							
							<Label class="readonly">
								<span class="input-description">Тариф, грн/1 м<sup>3</sup> з ПДВ</span>
								<input class="form-control" type="text" readonly />
							</Label>
							
							<Label class="readonly">
								<span class="input-description">Рекомендована мінімальна кількість вивезень на тиждень &#8776;</span>
								<input class="form-control" type="text" readonly style="font-weight: bold;" />
							</Label>
							
							<Label class="readonly">
								<span class="input-description">*Вартість послуг за місяць, грн. з ПДВ &#8776;</span>
								<input class="form-control" type="text" readonly style="font-weight: bold;" />
							</Label>
							
							<Label class="readonly">
								<span class="input-description">**Потреба в контейнерах, шт.</span>
								<input class="form-control" type="text" readonly style="font-weight: bold;" />
							</Label>
							
							<p>*Остаточну вартість та умови визначає менеджер при оформленні!</p>
							
							<a class="btn-red" href="/contacts">Оформити заявку</a>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
