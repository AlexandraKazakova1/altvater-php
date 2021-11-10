@extends('account.layout')

@section('main')
	<main>
		<h1 class="page__title">Мій кабінет</h1>
		
		<div class="content__wrapper">
			<div class="content">
				<section class="account__wrapper">
					<h2 class="section__title">Особистий акаунт</h2>
					
					<div class="account__info">
						<div class="owner">
							<svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14.7157 11.8986C15.9971 13.18 15.9971 15.2575 14.7157 16.5389C13.4343 17.8204 11.3567 17.8204 10.0753 16.5389C8.79391 15.2575 8.79391 13.18 10.0753 11.8986C11.3567 10.6171 13.4343 10.6171 14.7157 11.8986" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M2.9165 26.9785V8.07997C2.9165 6.43643 4.24942 5.10352 5.89296 5.10352H29.1665C30.778 5.10352 32.0832 6.40872 32.0832 8.02018V26.9785C32.0832 28.59 30.778 29.8952 29.1665 29.8952H5.83317C4.22171 29.8952 2.9165 28.59 2.9165 26.9785Z" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M21.875 13.8542H27.7083" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M25.375 19.6862H21.875" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M17.6387 24.0612C17.3835 23.421 16.9898 22.8464 16.4837 22.3783V22.3783C15.6277 21.5849 14.5048 21.1445 13.3381 21.1445H11.4539C10.2873 21.1445 9.16436 21.5849 8.30832 22.3783V22.3783C7.80228 22.8464 7.40853 23.421 7.15332 24.0612" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<div>
								<span>Ім'я:</span>
								<span class="owner-name">{{$user['name']}}</span>
							</div>
						</div>
						
						<div class="owner__id__wrapper">
							<svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="17.4996" cy="17.4996" r="13.1305" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M23.3356 23.3354C23.166 22.9085 22.9027 22.5251 22.5653 22.2135V22.2135C21.9967 21.685 21.2494 21.3911 20.4731 21.3906H14.528C13.7513 21.3911 13.0035 21.685 12.4344 22.2135V22.2135C12.0977 22.5258 11.8346 22.909 11.6641 23.3354" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<ellipse cx="17.4999" cy="14.9467" rx="3.28262" ry="3.28262" stroke="#2F5E97" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<div>
								<span>Номер облікового запису:</span>
								<span class="owner-id">{{$user['id']}}</span>
							</div>
						</div>
					</div>
				</section>
				
				<section class="volume__wrapper">
					<div class="title__wrapper with-select">
						<h2 class="section__title">Об'єми відходів</h2>
						
						<select class="filter custom-select">
							<option>Тиждень</option>
							<option>Місяць</option>
							<option>Рік</option>
						</select>
					</div>
					
					<div class="volume">
						<span>200,20 Тонн</span>
						<div class="chart-view">
							<canvas id="myChart"></canvas>
						</div>
					</div>
				</section>
			</div>
			
			<aside class="addresses__wrapper">
				<section class="addresses">
					<h2 class="section__title">Адреси обслуговування:</h2>
					
					<ul class="addresses__list" id="addresses__list">
						<!-- -->
						<li class="list__item" data-id="0" style="display:none;">
							<div class="item__body">
								<div class="item__img">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M12 13V13C10.343 13 9 11.657 9 10V10C9 8.343 10.343 7 12 7V7C13.657 7 15 8.343 15 10V10C15 11.657 13.657 13 12 13Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										<path fill-rule="evenodd" clip-rule="evenodd" d="M12 21C12 21 5 15.25 5 10C5 6.134 8.134 3 12 3C15.866 3 19 6.134 19 10C19 15.25 12 21 12 21Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</div>
								
								<div class="item__text">
									<span>Назва адреси:</span>
									<span class="name"></span>
								</div>
							</div>
							
							<button class="btn-more btn-address__info" data-id="0" data-lat="0" data-lng="0" data-name="" data-address="" data-toggle="modal" data-target="#address__info-modal">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
								</svg>
							</button>
						</li>
						<!-- -->
						
						@foreach($addresses as $item)
							<!-- -->
							<li class="list__item" data-id="{{$item->id}}">
								<div class="item__body">
									<div class="item__img">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12 13V13C10.343 13 9 11.657 9 10V10C9 8.343 10.343 7 12 7V7C13.657 7 15 8.343 15 10V10C15 11.657 13.657 13 12 13Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12 21C12 21 5 15.25 5 10C5 6.134 8.134 3 12 3C15.866 3 19 6.134 19 10C19 15.25 12 21 12 21Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
									
									<div class="item__text">
										<span>Назва адреси:</span>
										<span class="name">{{$item->name}}</span>
									</div>
								</div>
								
								<button class="btn-more btn-address__info" data-id="{{$item->id}}" data-lat="{{$item->lat}}" data-lng="{{$item->lng}}" data-name="{{$item->name}}" data-address="{{$item->address}}" data-toggle="modal" data-target="#address__info-modal">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
										<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
										<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
									</svg>
								</button>
							</li>
							<!-- -->
						@endforeach
					</ul>
					
					<button class="btn-add__address add-address" data-toggle="modal" data-target="#add__address-modal">Додати адресу обслуговування</button>
				</section>
			</aside>
		</div>
	</main>
@stop
