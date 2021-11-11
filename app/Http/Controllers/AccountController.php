<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Contracts;
use App\Models\Bills;
use App\Models\Acts;
use App\Models\Orders;
use App\Models\OrdersServices;
use App\Models\UserAddresses;

use App\Models\Dialogues;
use App\Models\Messages;
use App\Models\Themes;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class AccountController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$addresses = UserAddresses::query()->where('client_id', $this->_id)->orderBy('name', 'asc')->get();
		
		foreach($addresses as $i => $item){
			$imgs = [];
			
			foreach($item->images as $img){
				$imgs[] = url('storage/'.$img->file);
			}
			
			$addresses[$i]->images = $imgs;
		}
		
		return view(
			'account/index',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.index.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/index',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'data'			=> [],
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
				'addresses'		=> $addresses
			]
		);
	}
	
	public function contracts(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$limit = 4;
		
		return view(
			'account/contracts',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.contracts.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/contracts',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'limit'			=> $limit,
				'count'			=> Contracts::query()->where('client_id', $this->_id)->whereRaw(DB::raw('(`archive` IS NULL OR `archive` = 0)'))->count(),
				'count_archive'	=> Contracts::query()->where('client_id', $this->_id)->where('archive', 1)->count(),
				'contracts'		=> [
					'active'		=> Contracts::query()->where('client_id', $this->_id)->whereRaw(DB::raw('(`archive` IS NULL OR `archive` = 0)'))->skip(0)->take($limit)->orderBy('created_at', 'desc')->get(),
					'archive'		=> Contracts::query()->where('client_id', $this->_id)->where('archive', 1)->skip(0)->take($limit)->orderBy('created_at', 'desc')->get(),
				],
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
			]
		);
	}
	
	public function contract(Request $request){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$id = $request->route('id');
		
		$contract = Contracts::query()->where('client_id', $this->_id)->where('id', $id)->first();
		
		if(!$contract){
			return redirect('/account/contracts');
		}
		
		return view(
			'account/contract',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.contracts.title').' - №'.$contract->number,
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/contract',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'contract'		=> $contract,
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
			]
		);
	}
	
	public function bills(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$limit = 4;
		
		return view(
			'account/bills',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.bills.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/bills',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'limit'			=> $limit,
				'count'			=> [
					'bills'			=> Bills::query()->where('client_id', $this->_id)->count(),
					'acts'			=> Acts::query()->where('client_id', $this->_id)->count(),
				],
				'data'			=> [
					'bills'			=> Bills::query()->where('client_id', $this->_id)->skip(0)->take($limit)->orderBy('created_at', 'desc')->get(),
					'acts'			=> Acts::query()->where('client_id', $this->_id)->skip(0)->take($limit)->orderBy('created_at', 'desc')->get()
				],
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
			]
		);
	}
	
	public function bill(Request $request){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$id = $request->route('id');
		
		$bill = Bills::query()->where('client_id', $this->_id)->where('id', $id)->first();
		
		if(!$bill){
			return redirect('/account/bills');
		}
		
		return view(
			'account/bill',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.bills.title').' - №'.$bill->number,
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/bills/'.$bill->id,
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
				'bill'			=> $bill
			]
		);
	}
	
	public function act(Request $request){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$id = $request->route('id');
		
		$act = Acts::query()->where('client_id', $this->_id)->where('id', $id)->first();
		
		if(!$act){
			return redirect('/account/bills');
		}
		
		return view(
			'account/act',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.acts.title').' - №'.$act->number,
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/bills/act/'.$act->id,
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
				'act'			=> $act
			]
		);
	}
	
	public function messages(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		return view(
			'account/messages',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.messages.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/messages',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'data'			=> Dialogues::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->get(),
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
				'themes'		=> Themes::query()->orderBy('name', 'asc')->get(),
				'contracts'		=> Contracts::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->select('id', 'number')->get()
			]
		);
	}
	
	public function dialog(Request $request){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		$id = $request->route('id');
		
		$dialog = Dialogues::query()->where('client_id', $this->_id)->where('id', $id)->first();
		
		if(!$dialog){
			return redirect('/account/messages');
		}
		
		Messages::query()->where('id', $id)->update(['read' => 1]);
		
		$messages = Messages::query()->where('dialogue_id', $id)->orderBy('created_at', 'asc')->get();
		
		return view(
			'account/dialog',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.messages.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/messages',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'dialog'		=> $dialog,
				'messages'		=> $messages,
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get()
			]
		);
	}
	
	public function orders(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		return view(
			'account/orders',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.orders.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/orders',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'count'			=> Orders::query()->where('client_id', $this->_id)->count(),
				'data'			=> Orders::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->get(),
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
			]
		);
	}
	
	public function settings(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		return view(
			'account/settings',
			[
				'page'			=> array(
					'title'			=> trans('site.cabinet.settings.title'),
					'keywords'		=> '',
					'description'	=> '',
					'uri'			=> 'account/settings',
					'og_image'		=> '',
				),
				'headerClass'	=> '',
				'robots'		=> '',
				'canonical'		=> '',
				'data'			=> [],
				'services'		=> OrdersServices::query()->orderBy('name', 'asc')->get(),
			]
		);
	}
	
	public function logout(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
		Auth::logout();
		
		return redirect('/');
	}
}
