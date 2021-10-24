<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Contracts;

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
				'data'			=> []
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
				'data'			=> []
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
				'data'			=> []
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
