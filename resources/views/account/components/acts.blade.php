@php $status = ["1"=>"signed","2"=>"notSigned","3"=>"read","4"=>"notRead"]; @endphp
@foreach($acts as $item)
	<!-- -->
	<div class="accountsActs__item acts__item {{(isset($status[$item->status]) ? $status[$item->status] : '')}}">
		<span>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 13.1046 17.8954 14 19 14C20.1046 14 21 13.1046 21 12C21 10.8954 20.1046 10 19 10C17.8954 10 17 10.8954 17 12Z"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 13.1046 3.89543 14 5 14C6.10457 14 7 13.1046 7 12C7 10.8954 6.10457 10 5 10C3.89543 10 3 10.8954 3 12Z"/>
			</svg>
		</span>
		
		<div class="number">
			Номер акту:
			<span>{{$item->number}}</span>
		</div>
		
		<div class="date">
			Дата виставлення акту:
			<span>{{($item->date ? $string->call('date2Str', [$item->date]) : '-')}}</span>
		</div>
		
		<div class="payer">
			Кому виставлений:
			<span>{{($item->name ? $item->name : '-')}}</span>
		</div>
		
		<div class="status"></div>
		
		<div class="download">
			<a href="/account/bills/act/{{$item->id}}">
				<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12.1213 5.87975C13.2923 7.05075 13.2923 8.95175 12.1213 10.1248C10.9503 11.2958 9.0493 11.2958 7.8763 10.1248C6.7053 8.95375 6.7053 7.05275 7.8763 5.87975C9.0493 4.70675 10.9493 4.70675 12.1213 5.87975" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<path fill-rule="evenodd" clip-rule="evenodd" d="M1 8C1 7.341 1.152 6.689 1.446 6.088V6.088C2.961 2.991 6.309 1 10 1C13.691 1 17.039 2.991 18.554 6.088V6.088C18.848 6.689 19 7.341 19 8C19 8.659 18.848 9.311 18.554 9.912V9.912C17.039 13.009 13.691 15 10 15C6.309 15 2.961 13.009 1.446 9.912V9.912C1.152 9.311 1 8.659 1 8Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				<span>Переглянути</span>
			</a>
		</div>
	</div>
	<!-- -->
@endforeach
