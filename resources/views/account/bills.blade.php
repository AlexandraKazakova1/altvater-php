@extends('account.layout')

@section('main')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/account">Кабінет</a></li>
					<li class="breadcrumbs__item active">
						<span>Мої рахунки</span>
					</li>
				</ul>
			</div>
		</section>
		
		<h1 class="page__title">Мої рахунки</h1>
		
		<a class="back-link" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="accountsActs__wrapper">
			<div class="selector">
				<ul>
					<li class="act" id="accountsToggle">Рахунки</li>
					<li id="actsToggle">Акти</li>
				</ul>
			</div>
			
			<div class="accounts__content">
				<div class="filters">
					<div>
						Всього: <span class="counter">18</span>
					</div>
					
					<ul class="filters__list">
						<li>Сортування:</li>
						<li>
							<button>
								Дата
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
						<li>
							<button>
								Номер
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
						<li>
							<button>
								Cтатус
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
					</ul>
				</div>
				
				<div class="accountsActs__list accounts__list">
					<!-- -->
					<div class="accountsActs__item accounts__item paid">
						<span>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
							</svg>
						</span>
						
						<div class="number">
							Номер рахунку:
							<span>847567</span>
						</div>
						
						<div class="date">
							Дата виставлення рахунку:
							<span>28 Серпня 2021 року</span>
						</div>
						
						<div class="payer">
							Кому виставлений:
							<span>Порошенку Петру Олексійовичу</span>
						</div>
						
						<div class="cost">
							Вартість:
							<span>253 345.32</span>
						</div>
						
						<div class="status"></div>
					</div>
					<!-- -->
				</div>
			</div>
			
			<div class="acts__content">
				<div class="filters">
					<div class="total__counters">
						Всього: <span class="counter">18</span>
					</div>
					
					<ul class="filters__list">
						<li>Сортування:</li>
						<li>
							<button>
								Дата
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
						<li>
							<button>
								Номер
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
						<li>
							<button>
								Cтатус
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.5L12 14.5L7 9.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</li>
					</ul>
				</div>
				
				<div class="acts__list">
					<!-- -->
					<div class="accountsActs__item acts__item signed">
						<span>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
							</svg>
						</span>
						
						<div class="number">
							Номер акту:
							<span>847567</span>
						</div>
						
						<div class="date">
							Дата виставлення акту:
							<span>28 Серпня 2021 року</span>
						</div>
						
						<div class="payer">
							Кому виставлений:
							<span>Порошенку Петру Олексійовичу</span>
						</div>
						
						<div class="status"></div>
					</div>
					<!-- -->
				</div>
			</div>
		</section>
	</main>
@stop
