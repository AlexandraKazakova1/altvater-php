@extends('account.layout')

@section('main')
	<main>
		<section class="breadcrumbs__wrapper">
			<div class="breadcrumbs container">
				<ul class="breadcrumbs__list">
					<li class="breadcrumbs__item"><a href="/account">Кабінет</a></li>
					<li class="breadcrumbs__item active">
						<span>Мої договори</span>
					</li>
				</ul>
			</div>
		</section>
		
		<div class="title__wrap">
			<h1 class="page__title">Мої договори</h1>
			
			<div class="btn-group">
				<button class="pinned">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M5.25781 18.9607L9.47227 14.7207" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M17.3816 9.41342L19.4411 9.52342C19.7224 9.53842 19.9977 9.43242 20.1965 9.23242L20.6309 8.79542C21.0195 8.40442 21.0195 7.77142 20.6309 7.38142L16.7673 3.49442C16.3787 3.10342 15.7495 3.10342 15.3618 3.49442L15.0378 3.81942C14.8509 4.00742 14.7466 4.26142 14.7466 4.52642V6.76242L12.1115 9.41342H8.57198C8.30857 9.41342 8.05511 9.51842 7.86923 9.70642L6.88719 10.6944C6.49854 11.0854 6.49854 11.7184 6.88719 12.1084L9.47749 14.7144L12.0678 17.3204C12.4564 17.7114 13.0856 17.7114 13.4733 17.3204L14.4553 16.3324C14.6422 16.1444 14.7466 15.8904 14.7466 15.6254V12.0634L17.3816 9.41342Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M17.385 9.40977L14.751 6.75977" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M7.49438 21.2109L3.02148 16.7109" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Приєднати
				</button>
				
				<button class="add">
					<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12.1375 8V16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M16.1832 12H8.0918" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M12.137 21V21C7.10923 21 3.03418 16.971 3.03418 12V12C3.03418 7.029 7.10923 3 12.137 3V3C17.1648 3 21.2399 7.029 21.2399 12V12C21.2399 16.971 17.1648 21 12.137 21Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Новий договір
				</button>
			</div>
		</div>
		
		<a class="back-link wb" href="/account">
			<img src="/img/cabinet/arrow-red.svg">
			<span>Назад</span>
		</a>
		
		<section class="contracts__clear" style="{{(($count > 0 || $count_archive > 0) ? 'display:none;' : '')}}">
			<img src="/img/cabinet/noContracts.png">
		</section>
		
		<section class="contracts__wrapper" id="contracts" style="{{(($count > 0 || $count_archive > 0) ? '' : 'display:none;')}}">
			<div class="selector">
				<ul>
					<li data-archive="0" data-count="{{$count}}" class="act" id="contractsToggle">Документи</li>
					<li data-archive="1" data-count="{{$count_archive}}" id="archiveToggle">Архів</li>
				</ul>
			</div>
			
			<div class="contracts__content">
				<div class="filters">
					<div>
						Кількість договорів: <span class="counter">{{$count}}</span>
					</div>
					
					<div class="filters__list">
						<p>Сортування:</p>
						<select class="custom-select" id="sort-active-contracts" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
						</select>
					</div>
				</div>
				
				<div class="contracts__list" id="active-contracts" data-limit="{{$limit}}" data-count="{{$count}}">
					@include('account.components.active_contracts', ['contracts' => $contracts['active']])
					
					<button style="{{($count > count($contracts['active']) ? '' : 'display:none;')}}" class="more">Показати всі</button>
				</div>
			</div>
			
			<div class="archive__content">
				<div class="filters">
					<div>
						Кількість договорів: <span class="counter">{{$count_archive}}</span>
					</div>
					
					<div class="filters__list">
						<p>Сортування:</p>
						<select class="custom-select" id="sort-archive-contracts" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
						</select>
					</div>
				</div>
				
				<div class="contracts__list" id="archive-contracts" data-limit="{{$limit}}" data-count="{{$count_archive}}">
					@include('account.components.archive_contracts', ['contracts' => $contracts['archive']])
					
					<button style="{{($count_archive > count($contracts['archive']) ? '' : 'display:none;')}}" class="more">Показати всі</button>
				</div>
			</div>
		</section>
	</main>
@stop
