<?php

namespace App\Http\Controllers;

use App\Models\Pages;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class AccountController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->session();
		
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
				'data'			=> []
			]
		);
	}
	
	public function bills(){
		$this->session();
		
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
}
