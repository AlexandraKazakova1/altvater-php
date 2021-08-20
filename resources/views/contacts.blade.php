@extends('layout')

@section('content')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/">Veolia</a></li>
					<li class="breadcrumbs__item active">
						<span>Контакти</span>
						
						<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 1L6 6L1 1" stroke="#858585" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						
						<ul class="dropdown__list">
							<!-- -->
							<li class="dropdown__link">
								<a href="#">
									Послуги
								</a>
							</li>
							<!-- -->
						</ul>
					</li>
				</ul>
			</div>
		</section>
		
		<section class="contacts__wrapper">
			<div class="contacts container">
				<ul class="contacts__list">
					@if($contacts['address'])
						<!-- -->
						<li class="contacts__item">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M13.5904 22.2957C16.1746 20.1791 21 15.4917 21 10C21 5.02944 16.9706 1 12 1C7.02944 1 3 5.02944 3 10C3 15.4917 7.82537 20.1791 10.4096 22.2957C11.3466 23.0631 12.6534 23.0631 13.5904 22.2957ZM12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z" fill="#00B7CE"/>
							</svg>
							
							<div class="item__body">
								<span class="subTitle21">Адреса:</span>
								@foreach($contacts['address'] as $item)
									<span>{!!$item!!}</span>
								@endforeach
							</div>
						</li>
						<!-- -->
					@endif
					@if($contacts['phone'])
						<!-- -->
						<li class="contacts__item">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0)">
									<path d="M21.3902 19.5804L19.4852 21.4853C19.4852 21.4853 14.5355 23.6066 7.46441 16.5356C0.39334 9.46451 2.51466 4.51476 2.51466 4.51476L4.41959 2.60983C5.28021 1.74921 6.70355 1.85036 7.43381 2.82404L9.25208 5.24841C9.84926 6.04465 9.77008 7.15884 9.06629 7.86262L7.46441 9.46451C7.46441 9.46451 7.46441 10.8787 10.2928 13.7071C13.1213 16.5356 14.5355 16.5356 14.5355 16.5356L16.1374 14.9337C16.8411 14.2299 17.9553 14.1507 18.7516 14.7479L21.1759 16.5662C22.1496 17.2964 22.2508 18.7198 21.3902 19.5804Z" fill="#00B7CE"/>
								</g>
							</svg>
							<div class="item__body tel-button">
								<span class="subTitle21">Тел/Факс:</span>
								@foreach($contacts['phone'] as $item)
									<a href="tel:+{{$item}}">{{$string->call('phone', [$item])}}</a>
								@endforeach
							</div>
						</li>
						<!-- -->
					@endif
					@if($contacts['email'])
						<!-- -->
						<li class="contacts__item">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M4 3C2.34315 3 1 4.34315 1 6V18C1 19.6569 2.34315 21 4 21H20C21.6569 21 23 19.6569 23 18V6C23 4.34315 21.6569 3 20 3H4ZM6.64021 7.2318C6.21593 6.87824 5.58537 6.93556 5.2318 7.35984C4.87824 7.78412 4.93556 8.41468 5.35984 8.76825L10.0795 12.7013C11.192 13.6284 12.808 13.6284 13.9206 12.7013L18.6402 8.76825C19.0645 8.41468 19.1218 7.78412 18.7682 7.35984C18.4147 6.93556 17.7841 6.87824 17.3598 7.2318L12.6402 11.1648C12.2694 11.4739 11.7307 11.4739 11.3598 11.1648L6.64021 7.2318Z" fill="#00B7CE"/>
							</svg>
							<div class="item__body">
								<span class="subTitle21">Email:</span>
								@foreach($contacts['email'] as $item)
									<a href="mailto:{{$item}}">{{$item}}</a>
								@endforeach
							</div>
						</li>
						<!-- -->
					@endif
					@if($contacts['work'])
						<!-- -->
						<li class="contacts__item">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM13 7C13 6.44772 12.5523 6 12 6C11.4477 6 11 6.44772 11 7V12C11 12.2652 11.1054 12.5196 11.2929 12.7071L13.7929 15.2071C14.1834 15.5976 14.8166 15.5976 15.2071 15.2071C15.5976 14.8166 15.5976 14.1834 15.2071 13.7929L13 11.5858V7Z" fill="#00B7CE"/>
							</svg>
							<div class="item__body">
								<span class="subTitle21">Графік роботи:</span>
								@foreach($contacts['work'] as $item)
									<span>{!!$item!!}</span>
								@endforeach
							</div>
						</li>
						<!-- -->
					@endif
					
				</ul>
				
				<div class="callback">
					<div class="callback__title">Залишіть заявку</div>
					<div class="callback__descriptin">Якщо хочете дізнатися про всі можливості сервісу і задати питання - замовте онлайн демонстрацію</div>
					
					<form class="callback__form" id="callback-form">
						<fieldset class="callback-fieldset">
							<label>
								<span>Ім'я </span>
								<input class="form-control" type="text" id="username" name="username" placeholder="Ваше Ім'я">
							</label>
							
							<label>
								<span>Email</span>
								<input class="form-control" type="email" id="useremail" name="useremail" placeholder="Ваш email">
							</label>
							
							<label>
								<span>Повідомлення</span>
								<textarea name="message" placeholder="Ваше Повідомлення "></textarea>
							</label>
							
							<button class="btn-submit" type="submit" name="comment">Залишити заявку</button>
							
							<label class="checkbox__label">
								<input class="input-checkbox" type="checkbox" id="rule" name="rule">
								<span class="custom-checkbox"></span>
								<span class="checkbox-text">Підтверджуючи замовлення, я приймаю умови угоди користувача</span>
							</label>
						</fieldset>
					</form>
				</div>
			</div>
		</section>
		
		@if($settings['map_url'])
			<section class="map__wrapper">
				<div class="map">
					<iframe src="{!!$settings['map_url']!!}" width="100%" height="530px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				</div>
			</section>
		@endif
	</main>
@stop
