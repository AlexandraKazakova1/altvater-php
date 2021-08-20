<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Contacts;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class ContactsController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$page = (object)Pages::query()->where('slug', 'contacts')->first()->toArray();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'contacts',
				'og_image'		=> '',
			),
			'headerClass'	=> 'background-2',
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
			'data'			=> $page,
			'contacts'		=> Contacts::getData(),
		];
		
		return view(
			'contacts',
			$data
		);
	}
}
