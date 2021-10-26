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
		
		<section class="contracts__wrapper" style="{{(($count > 0 || $count_archive > 0) ? '' : 'display:none;')}}">
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
						<select class="custom-select" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
						</select>
					</div>
				</div>
				
				<div class="contracts__list">
					@foreach($contracts['active'] as $item)
						<!-- -->
						<div class="contract__item" data-archive="{{(int)($item->archive)}}">
							<span>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
								</svg>
							</span>
							
							<div class="number">
								ID договору:
								<span>{{$item->number}}</span>
							</div>
							
							<div class="docName">
								<span>{{($item->file_name ? $item->file_name : '-')}}</span>
							</div>
							
							<div class="date">
								Дата підписання:
								<span>{{($item->date ? implode('.', array_reverse(explode('-', $item->date))) : '-')}}</span>
							</div>
							
							<div class="download">
								@if($item->file)
									<a href="/account/contract/{{$item->id}}">
										<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.1213 5.87975C13.2923 7.05075 13.2923 8.95175 12.1213 10.1248C10.9503 11.2958 9.0493 11.2958 7.8763 10.1248C6.7053 8.95375 6.7053 7.05275 7.8763 5.87975C9.0493 4.70675 10.9493 4.70675 12.1213 5.87975" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M1 8C1 7.341 1.152 6.689 1.446 6.088V6.088C2.961 2.991 6.309 1 10 1C13.691 1 17.039 2.991 18.554 6.088V6.088C18.848 6.689 19 7.341 19 8C19 8.659 18.848 9.311 18.554 9.912V9.912C17.039 13.009 13.691 15 10 15C6.309 15 2.961 13.009 1.446 9.912V9.912C1.152 9.311 1 8.659 1 8Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										<span>Переглянути</span>
									</a>
									
									<a href="{{url('storage/'.$item->file)}}" download="">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M4.40576 1.34182C5.31176 0.560456 6.57359 0 8 0C10.6902 0 12.9233 1.99944 13.1657 4.57898C14.7581 4.80411 16 6.13656 16 7.77273C16 9.56949 14.5023 11 12.6875 11H10C9.72386 11 9.5 10.7761 9.5 10.5C9.5 10.2239 9.72386 10 10 10H12.6875C13.9793 10 15 8.98842 15 7.77273C15 6.55703 13.9793 5.54545 12.6875 5.54545H12.1875V5.04545C12.1875 2.8256 10.3273 1 8 1C6.83758 1 5.80253 1.45773 5.05886 2.09909C4.30231 2.75157 3.90625 3.5383 3.90625 4.15455V4.6026L3.46088 4.65155C2.06371 4.80512 1 5.95266 1 7.31818C1 8.78492 2.23059 10 3.78125 10H6C6.27614 10 6.5 10.2239 6.5 10.5C6.5 10.7761 6.27614 11 6 11H3.78125C1.70754 11 0 9.36599 0 7.31818C0 5.55511 1.26586 4.09512 2.94223 3.725C3.08479 2.8617 3.63985 2.00237 4.40576 1.34182Z"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M7.64645 15.8536C7.84171 16.0488 8.15829 16.0488 8.35355 15.8536L11.3536 12.8536C11.5488 12.6583 11.5488 12.3417 11.3536 12.1464C11.1583 11.9512 10.8417 11.9512 10.6464 12.1464L8.5 14.2929V5.5C8.5 5.22386 8.27614 5 8 5C7.72386 5 7.5 5.22386 7.5 5.5V14.2929L5.35355 12.1464C5.15829 11.9512 4.84171 11.9512 4.64645 12.1464C4.45118 12.3417 4.45118 12.6583 4.64645 12.8536L7.64645 15.8536Z"/>
										</svg>
										<span>Завантажити</span>
									</a>
								@endif
							</div>
						</div>
						<!-- -->
					@endforeach
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
						<select class="custom-select" name="contracts__filter">
							<option value="date">Дата</option>
							<option value="number">Номер</option>
						</select>
					</div>
				</div>
				
				<div class="contracts__list">
					@foreach($contracts['archive'] as $item)
						<!-- -->
						<div class="contract__item" data-archive="{{(int)($item->archive)}}">
							<span>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
								</svg>
							</span>
							
							<div class="number">
								ID договору:
								<span>{{$item->number}}</span>
							</div>
							
							<div class="docName">
								<span>{{($item->file_name ? $item->file_name : '-')}}</span>
							</div>
							
							<div class="date">
								Дата підписання:
								<span>{{($item->date ? implode('.', array_reverse(explode('-', $item->date))) : '-')}}</span>
							</div>
							
							<div class="download">
								@if($item->file)
									<a href="/account/contract/{{$item->id}}">
										<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.1213 5.87975C13.2923 7.05075 13.2923 8.95175 12.1213 10.1248C10.9503 11.2958 9.0493 11.2958 7.8763 10.1248C6.7053 8.95375 6.7053 7.05275 7.8763 5.87975C9.0493 4.70675 10.9493 4.70675 12.1213 5.87975" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M1 8C1 7.341 1.152 6.689 1.446 6.088V6.088C2.961 2.991 6.309 1 10 1C13.691 1 17.039 2.991 18.554 6.088V6.088C18.848 6.689 19 7.341 19 8C19 8.659 18.848 9.311 18.554 9.912V9.912C17.039 13.009 13.691 15 10 15C6.309 15 2.961 13.009 1.446 9.912V9.912C1.152 9.311 1 8.659 1 8Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										<span>Переглянути</span>
									</a>
									
									<a href="{{url('storage/'.$item->file)}}" download="">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M4.40576 1.34182C5.31176 0.560456 6.57359 0 8 0C10.6902 0 12.9233 1.99944 13.1657 4.57898C14.7581 4.80411 16 6.13656 16 7.77273C16 9.56949 14.5023 11 12.6875 11H10C9.72386 11 9.5 10.7761 9.5 10.5C9.5 10.2239 9.72386 10 10 10H12.6875C13.9793 10 15 8.98842 15 7.77273C15 6.55703 13.9793 5.54545 12.6875 5.54545H12.1875V5.04545C12.1875 2.8256 10.3273 1 8 1C6.83758 1 5.80253 1.45773 5.05886 2.09909C4.30231 2.75157 3.90625 3.5383 3.90625 4.15455V4.6026L3.46088 4.65155C2.06371 4.80512 1 5.95266 1 7.31818C1 8.78492 2.23059 10 3.78125 10H6C6.27614 10 6.5 10.2239 6.5 10.5C6.5 10.7761 6.27614 11 6 11H3.78125C1.70754 11 0 9.36599 0 7.31818C0 5.55511 1.26586 4.09512 2.94223 3.725C3.08479 2.8617 3.63985 2.00237 4.40576 1.34182Z"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M7.64645 15.8536C7.84171 16.0488 8.15829 16.0488 8.35355 15.8536L11.3536 12.8536C11.5488 12.6583 11.5488 12.3417 11.3536 12.1464C11.1583 11.9512 10.8417 11.9512 10.6464 12.1464L8.5 14.2929V5.5C8.5 5.22386 8.27614 5 8 5C7.72386 5 7.5 5.22386 7.5 5.5V14.2929L5.35355 12.1464C5.15829 11.9512 4.84171 11.9512 4.64645 12.1464C4.45118 12.3417 4.45118 12.6583 4.64645 12.8536L7.64645 15.8536Z"/>
										</svg>
										<span>Завантажити</span>
									</a>
								@endif
							</div>
						</div>
						<!-- -->
					@endforeach
					<button style="{{($count_archive > count($contracts['archive']) ? '' : 'display:none;')}}" class="more">Показати всі</button>
				</div>
			</div>
		</section>
	</main>
@stop
