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
		
		<section class="accountsActs__wrapper" id="bills" >
			<div class="selector">
				<ul>
					<li class="act" id="accountsToggle">Рахунки</li>
					<li id="actsToggle">Акти</li>
				</ul>
			</div>
			
			<div class="accounts__content">
				<div class="filters">
					<div>
						Всього: <span class="counter" id="count_bills">{{$count['bills']}}</span>
					</div>
					
					<div class="filters__list">
						<p>Сортування:</p>
						<select class="custom-select" id="sort-bills" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
							<option value="status">Cтатус</option>
						</select>
					</div>
				</div>
				
				<div class="accountsActs__list accounts__list" id="bills-list" data-limit="{{$limit}}" data-count="{{$count['bills']}}" data-show="{{count($data['bills'])}}">
					@include('account.components.bills', ['bills' => $data['bills']])
					
					<button style="{{($count['bills'] > count($data['bills']) ? '' : 'display:none;')}}" class="more">Показати більше</button>
				</div>
			</div>
			
			<div class="acts__content">
				<div class="filters">
					<div class="total__counters">
						Всього: <span class="counter" id="count_acts">{{$count['acts']}}</span>
					</div>
					
					<div class="filters__list">
						<p>Сортування:</p>
						<select class="custom-select" id="sort-acts" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
							<option value="status">Cтатус</option>
						</select>
					</div>
				</div>
				
				<div class="acts__list" id="acts-list" data-limit="{{$limit}}" data-count="{{$count['acts']}}" data-show="{{count($data['acts'])}}">
					@include('account.components.acts', ['acts' => $data['acts']])
					
					<button style="{{($count['acts'] > count($data['acts']) ? '' : 'display:none;')}}" class="more">Показати більше</button>
				</div>
			</div>
		</section>
	</main>
@stop
