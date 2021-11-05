@extends('layout')

@section('content')
	<main class="main-page">
		@if(count($services))
			<section class="services-wrapper" id="our-services">
				<div class="services container">
					<h2 class="section-title">Наші послуги</h2>
					
					<div class="services__list">
						@foreach($services as $item)
							<!-- -->
							<div class="services__item">
								<a class="services__photo-link" href="/services/{{$item->slug}}"><img class="services__photo" src="/storage/{{$item->image}}" alt="{{$item->title}}"></a>
								
								<span class="services__text">{!!$item->title!!}</span>
								
								<a class="services__link" href="/services/{{$item->slug}}">
									<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M23.8643 14.1685L5.73926 14.1685" stroke="white" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="white" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<span>Детальніше</span>
								</a>
							</div>
							<!-- -->
						@endforeach
					</div>
					<button class="calc-btn btn-red" data-toggle="modal" data-target="#servicesCalc">Розрахунок вартості наших послуг</button>
				</div>
			</section>
		@endif
		
		<section class="about-wrapper">
			<div class="about container">
				<h2 class="section-title">{!!$data->about_header!!}</h2>
				
				<div class="about-content">
					@if($data->about_left || $data->about_right)
						<div class="description-group">
							@if($data->about_left)
								<p class="text">{!!$data->about_left!!}</p>
							@endif
							@if($data->about_right)
								<p class="text">{!!$data->about_right!!}</p>
							@endif
						</div>
					@endif
					
					@if($data->text)
						<div class="detail__text">
							{!!$data->text!!}
						</div>
					@endif
					
					@if($data->meta_public)
						<div class="goal">
							@if($data->meta_header)
								<h3 class="goal__title subTitle21">{!!$data->meta_header!!}</h3>
							@endif
							@if($data->meta_text)
								<p class="goal__text">{!!$data->meta_text!!}</p>
							@endif
						</div>
					@endif
					
					@if($data->meta_image)
						<div class="our-photo"><img src="/storage/{{$data->meta_image}}" alt="{!!$data->meta_header!!}"></div>
					@endif
				</div>
			</div>
		</section>
		
		@if(count($news))
			<section class="news-wrapper">
				<div class="news container">
					<h2 class="section-title">Новини нашої компанії</h2>
					
					<ul class="news__list">
						@foreach($news as $item)
							<!-- -->
							<li class="news__item">
								<a class="news__photo-link" style="width:100%;max-height:208px;overflow:hidden;" href="/news/{{$item->slug}}"><img style="width:100%;" class="news__photo" src="/storage/{{$item->image}}" alt="{!!$item->title!!}"></a>
								
								<div class="news__text">
									<span class="news__date">{{$item->date->d}} {{trans('site.months')[$item->date->m]}}, {{$item->date->y}}</span>
									<span>{!!$item->title!!}</span>
								</div>
								
								<a class="news__link" href="/news/{{$item->slug}}">
									<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M23.8643 14.1685L5.73926 14.1685" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M16.5537 6.88903L23.8641 14.168L16.5537 21.4482" stroke="#2C2C2C" stroke-width="1.8125" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									
									<span>Детальніше</span>
								</a>
							</li>
							<!-- -->
						@endforeach
					</ul>
				</div>
			</section>
		@endif
		
		@if(count($reviews))
			<section class="response-wrapper">
				<div class="response container">
					<h2 class="section-title">Відгуки наших клієнтів</h2>
					
					<div class="response__slider slider">
						@foreach($reviews as $item)
							<!-- -->
							<div class="slider__item">
								<div class="response__photo"><img src="/storage/{{$item->image}}" alt="{{strip_tags($item->name)}}"></div>
								
								<div class="response__body">
									<div class="quotes">
										<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
											<g opacity="0.2" clip-path="url(#clip0)">
												<path d="M0 32V59.4286H27.4286V32H9.14292C9.14292 21.9175 17.3461 13.7143 27.4286 13.7143V4.57142C12.3036 4.57142 0 16.875 0 32Z" fill="#7ABCCE"/>
												<path d="M64.0009 13.7143V4.57142C48.8758 4.57142 36.5723 16.875 36.5723 32V59.4286H64.0009V32H45.7152C45.7152 21.9175 53.9184 13.7143 64.0009 13.7143Z" fill="#7ABCCE"/>
											</g>
										</svg>
									</div>
									<p class="response__text">{!!$item->text!!}</p>
									<span class="responser__name">{!!$item->name!!}</span>
								</div>
							</div>
							<!-- -->
						@endforeach
					</div>
				</div>
			</section>
		@endif
		
		@if($detail && $detail->text)
			<section class="detail__wrapper">
				<div class="detail container">
					<h2 class="section-title">{{$detail->header}}</h2>
					
					<div class="detail__text">
						{!!$detail->text!!}
					</div>
				</div>
			</section>
		@endif
		
		@if(count($faq))
			<section class="faq__wrapper">
				<div class="faq container">
					<h2 class="section-title">Відповіді на запитання</h2>
					
					<ul class="faq__question__list">
						@foreach($faq as $item)
							<!-- -->
							<li class="faq__question">
								<span class="question">
									{!!$item->title!!}
									<span class="arrow">
										<svg width="21" height="13" viewBox="0 0 21 13" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.5007 11.0832L9.79354 11.7903C10.1841 12.1808 10.8172 12.1808 11.2078 11.7903L10.5007 11.0832ZM20.3744 2.62361C20.7649 2.23309 20.7649 1.59992 20.3744 1.2094C19.9839 0.818873 19.3507 0.818873 18.9602 1.2094L20.3744 2.62361ZM0.626878 2.62361L9.79354 11.7903L11.2078 10.3761L2.04109 1.2094L0.626878 2.62361ZM11.2078 11.7903L20.3744 2.62361L18.9602 1.2094L9.79354 10.3761L11.2078 11.7903Z"/>
										</svg>
									</span>
								</span>
								<span class="reply">{!!$item->text!!}</span>
							</li>
							<!-- -->
						@endforeach
					</ul>
				</div>
			</section>
		@endif

		
		
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
								<span class="subTitle21">Зателефонуйте нам:</span>
								@foreach($contacts['phone'] as $item)
									@if($item->label)
										<span>{{$item->label}}</span>
									@endif
									<a href="tel:+{{$item->value}}" {!!($item->label ? 'style="margin-bottom:10px;"':'')!!}>+{{$string->call('phone', [$item->value, '[2] [(3)] 3-2-2'])}}</a>
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
					@if($contacts['viber'] || $contacts['telegram'])
						<!-- -->
						<li class="contacts__item">
							<div class="item__body bot__group">
								<span class="subTitle21">Наші боти:</span>
								
								@if($contacts['viber'])
									@foreach($contacts['viber'] as $item)
										<a href="https://viber.com/{{$item}}" class="bot__link">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M24 0H0V24H24V0Z" fill="#7D3DAF"/>
												<path d="M19.5905 7.73626L19.5857 7.7173C19.2023 6.16744 17.4739 4.50436 15.8867 4.15836L15.8688 4.15468C13.3015 3.66491 10.6984 3.66491 8.13159 4.15468L8.11315 4.15836C6.52643 4.50436 4.79804 6.16744 4.41413 7.7173L4.40992 7.73626C3.93595 9.9007 3.93595 12.0957 4.40992 14.2601L4.41413 14.2791C4.78166 15.7628 6.38134 17.3495 7.90935 17.7858V19.5159C7.90935 20.1421 8.67243 20.4496 9.10637 19.9978L10.8592 18.1757C11.2394 18.197 11.6198 18.2088 12.0002 18.2088C13.2925 18.2088 14.5854 18.0866 15.8688 17.8418L15.8867 17.8381C17.4739 17.4921 19.2023 15.829 19.5857 14.2791L19.5905 14.2602C20.0644 12.0957 20.0644 9.9007 19.5905 7.73626ZM18.2033 13.9462C17.9474 14.9574 16.635 16.2144 15.5923 16.4467C14.2272 16.7062 12.8514 16.8172 11.4769 16.7791C11.4495 16.7784 11.4233 16.789 11.4042 16.8086C11.2091 17.0088 10.1243 18.1224 10.1243 18.1224L8.76301 19.5195C8.66348 19.6233 8.48864 19.5527 8.48864 19.4095V16.5435C8.48864 16.4961 8.45483 16.4559 8.40833 16.4468C8.40806 16.4467 8.4078 16.4467 8.40754 16.4466C7.36482 16.2144 6.05299 14.9573 5.79652 13.9462C5.36996 11.9898 5.36996 10.0065 5.79652 8.05008C6.05299 7.03896 7.36482 5.7819 8.40754 5.54966C10.7916 5.09623 13.2088 5.09623 15.5923 5.54966C16.6355 5.7819 17.9474 7.03896 18.2033 8.05008C18.6304 10.0065 18.6304 11.9898 18.2033 13.9462Z" fill="white"/>
												<path d="M14.2685 15.2694C14.1082 15.2208 13.9554 15.1881 13.8136 15.1292C12.3434 14.5192 10.9904 13.7324 9.91868 12.5261C9.30922 11.8402 8.8322 11.0658 8.42896 10.2462C8.23774 9.85759 8.07659 9.45372 7.91234 9.05248C7.76257 8.68658 7.98317 8.30857 8.21547 8.03288C8.43344 7.77415 8.71392 7.57613 9.01768 7.43021C9.25476 7.3163 9.48864 7.38197 9.6618 7.58293C10.0361 8.01739 10.38 8.47409 10.6584 8.9777C10.8296 9.28746 10.7826 9.66611 10.4723 9.87692C10.3969 9.92816 10.3282 9.9883 10.2579 10.0462C10.1963 10.0969 10.1383 10.1481 10.0961 10.2168C10.0189 10.3425 10.0152 10.4906 10.0649 10.6273C10.4476 11.6788 11.0925 12.4965 12.151 12.9369C12.3203 13.0074 12.4904 13.0894 12.6855 13.0667C13.0123 13.0285 13.1181 12.6701 13.3471 12.4828C13.5709 12.2998 13.8569 12.2974 14.098 12.45C14.3391 12.6026 14.5729 12.7664 14.8053 12.932C15.0333 13.0945 15.2604 13.2535 15.4708 13.4388C15.6731 13.6169 15.7428 13.8506 15.6289 14.0924C15.4203 14.5352 15.1169 14.9036 14.6791 15.1387C14.5555 15.205 14.4079 15.2265 14.2685 15.2694C14.1082 15.2207 14.4079 15.2265 14.2685 15.2694Z" fill="white"/>
												<path d="M12.0044 6.92365C13.9274 6.97753 15.5068 8.2537 15.8452 10.1548C15.9029 10.4788 15.9234 10.81 15.9491 11.1387C15.9599 11.277 15.8816 11.4084 15.7323 11.4102C15.5782 11.412 15.5088 11.2831 15.4988 11.1448C15.479 10.8712 15.4653 10.5964 15.4276 10.325C15.2285 8.8925 14.0863 7.70738 12.6604 7.45307C12.4459 7.41478 12.2263 7.40473 12.0089 7.38192C11.8715 7.36749 11.6916 7.35917 11.6611 7.18839C11.6356 7.0452 11.7565 6.93118 11.8928 6.92386C11.9299 6.92181 11.9672 6.92349 12.0044 6.92365C13.9274 6.97753 11.9672 6.92349 12.0044 6.92365Z" fill="white"/>
												<path d="M14.9266 10.7121C14.9234 10.7361 14.9217 10.7926 14.9077 10.8457C14.8566 11.0388 14.5639 11.063 14.4965 10.8681C14.4766 10.8103 14.4736 10.7445 14.4735 10.6823C14.4728 10.275 14.3843 9.86811 14.1789 9.51375C13.9677 9.14953 13.6452 8.84335 13.2669 8.65808C13.0381 8.54607 12.7907 8.47645 12.54 8.43495C12.4304 8.41683 12.3196 8.40583 12.2095 8.3905C12.0761 8.37196 12.0048 8.28691 12.0111 8.15541C12.017 8.03218 12.1071 7.94355 12.2414 7.95114C12.6827 7.9762 13.1089 8.07158 13.5013 8.27933C14.2991 8.70184 14.7548 9.36877 14.8879 10.2593C14.8939 10.2996 14.9036 10.3396 14.9066 10.3801C14.9141 10.4801 14.9189 10.5802 14.9266 10.7121C14.9234 10.7361 14.9189 10.5802 14.9266 10.7121Z" fill="white"/>
												<path d="M13.7306 10.6655C13.5697 10.6684 13.4836 10.5794 13.467 10.4319C13.4555 10.329 13.4464 10.2248 13.4219 10.1247C13.3735 9.92767 13.2688 9.74504 13.103 9.62428C13.0247 9.56725 12.9361 9.52569 12.8432 9.49889C12.7251 9.46482 12.6026 9.47419 12.4848 9.44533C12.357 9.414 12.2862 9.31046 12.3064 9.1905C12.3246 9.08133 12.4309 8.99612 12.5501 9.00475C13.2957 9.05857 13.8285 9.44401 13.9046 10.3217C13.9099 10.3836 13.9163 10.4491 13.9025 10.5083C13.879 10.6098 13.8039 10.6606 13.7306 10.6655C13.5697 10.6684 13.8039 10.6606 13.7306 10.6655Z" fill="white"/>
											</svg>
											<span style="color: #7D3DAF;">Вайбер</span>
										</a>
									@endforeach
								@endif
								
								@if($contacts['telegram'])
									@foreach($contacts['telegram'] as $item)
										<a href="https://t.me/{{$item}}" class="bot__link">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24Z" fill="#039BE5"/>
												<path d="M5.49077 11.74L17.0608 7.27896C17.5978 7.08496 18.0668 7.40996 17.8928 8.22196L17.8938 8.22096L15.9238 17.502C15.7778 18.16 15.3868 18.32 14.8398 18.01L11.8398 15.799L10.3928 17.193C10.2328 17.353 10.0978 17.488 9.78777 17.488L10.0008 14.435L15.5608 9.41196C15.8028 9.19896 15.5068 9.07896 15.1878 9.29096L8.31678 13.617L5.35477 12.693C4.71177 12.489 4.69777 12.05 5.49077 11.74Z" fill="white"/>
											</svg>
											<span style="color: #039BE5;">Телеграм</span>
										</a>
									@endforeach
								@endif
							</div>
						</li>
						<!-- -->
					@endif
				</ul>
				
				<div class="callback">
					<div class="callback__title">Залишіть заявку</div>
					<div class="callback__description">Якщо хочете дізнатися про всі можливості сервісу або задати питання, заповніть, будь-ласка, форму</div>
					
					<form class="callback__form" id="callback-form" action="/ajax/callback" method="POST">
						<fieldset class="callback-fieldset">
							<label>
								<span>Ім'я </span>
								<input class="form-control" type="text" id="username" name="name" placeholder="Ваше Ім'я">
							</label>
							
							<label>
								<span>Email</span>
								<input class="form-control" type="email" id="useremail" name="email" placeholder="Ваш email">
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
					<span id="answer-msg"></span>
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
