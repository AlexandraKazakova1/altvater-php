@extends('account.layout')

@section('main')
	<main class="request__page">
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li><a href="/account">Кабінет</a></li>
					<li class="active">
						<span>Повідомлення</span>
					</li>
				</ul>
			</div>
		</section>
		
		<h1 class="page__title">Повідомлення</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="request">
			<form class="request__form" id="requestForm">
				<fieldset>
					<div>
						<label>
							<span class="input-description">Вибір тематики заявки:</span>
							<select class="custom-select" name="theme">
								<option data-id="0" class="first-option" disabled selected value hidden>Тематика заявки</option>
								@foreach($themes as $item)
									<option value="{{$item->id}}">{{$item->label}}</option>
								@endforeach
							</select>
						</label>
						
						<label>
							<span class="input-description">Номер договору:</span>
							<select class="custom-select" name="number">
								<option data-id="0" class="first-option" disabled selected value hidden>Номер договору</option>
								<option value="0">Без договору</option>
								
								@foreach($contracts as $item)
									<option value="{{$item->id}}">#{{$item->number}}</option>
								@endforeach
							</select>
						</label>
						
						<label>
							<span class="input-description notCheck">Телефон:</span>
							<input class="form-control" type="tel" name="phone" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/phone.svg">
								</div>
								Ваш телефон
							</span>
						</label>
					</div>
					
					<div>
						<label>
							<span class="input-description">Заголовок звернення:</span>
							<input class="form-control" type="text" name="header" placeholder=" ">
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/text.svg">
								</div>
								Заголовок
							</span>
						</label>
						
						<label>
							<span class="input-description">Коментар</span>
							<textarea placeholder=" " name="text"></textarea>
							<span class="input-placeholder">
								<div>
									<img src="/img/cabinet/messages.svg">
								</div>
								Коментар
							</span>
						</label>
					</div>
					
					<div>
						<label class="label__file">
							<input class="form-control-file" id="control-file" type="file" accept=".pdf, .jpg">
							
							<div>
								<img src="/img/File-fill.svg" alt="">
								<span>
									Прикріпити файл
									<span class="descriotion">pdf, jpg</span>
								</span>
							</div>
						</label>
						
						<ul class="addedFile" id="addedFile"></ul>
					</div>
					
					<div class="submit__wrap">
						<button class="btn-submit" type="submit" name="add-request">Залишити заявку</button>
					</div>
				</fieldset>
			</form>
		</section>
		
		<section class="messages__wrapper">
			<div class="messages__content">
				<div class="messages__list">
					@foreach($data as $item)
						<!-- -->
						<a href="/account/messages/{{$item->id}}" class="messages__item">
							<span>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
								</svg>
							</span>
							
							<div class="title">
								Тема:
								<span>{{$item->theme}}</span>
							</div>
							
							<div class="date">
								Останнє оновлення:
								<span>{{($item->updated_at ? implode('.', array_reverse(explode('-', explode(' ', $item->updated_at)[0]))) : '-')}}</span>
							</div>
						</a>
						<!-- -->
					@endforeach
				</div>
			</div>
		</section>
	</main>
@stop
