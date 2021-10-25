<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Contracts;
use App\Models\Bills;
use App\Models\Acts;
use App\Models\Orders;
use App\Models\OrdersServices;

use App\Models\Dialogues;
use App\Models\Messages;

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
				'data'			=> []
			]
		);
	}
	
	public function contracts(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
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
				'count'			=> Contracts::query()->where('client_id', $this->_id)->whereRaw(DB::raw('(`archive` IS NULL OR `archive` = 0)'))->count(),
				'count_archive'	=> Contracts::query()->where('client_id', $this->_id)->where('archive', 1)->count(),
				'contracts'		=> Contracts::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->get()
			]
		);
	}
	
	public function bills(){
		$this->session();
		
		if(!$this->_auth){
			return redirect('/');
		}
		
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
				'count'			=> [
					'bills'			=> Bills::query()->where('client_id', $this->_id)->count(),
					'acts'			=> Acts::query()->where('client_id', $this->_id)->count(),
				],
				'data'			=> [
					'bills'			=> Bills::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->get(),
					'acts'			=> Acts::query()->where('client_id', $this->_id)->orderBy('created_at', 'desc')->get()
				]
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
		
		$messages = Messages::query()->where('client_id', $this->_id)->where('dialogue_id', $id)->orderBy('created_at', 'desc')->get();
		
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
				'messages'		=> $messages
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
				'data'			=> []
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
