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

class AboutController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$page	= (object)Pages::query()->where('slug', 'about')->first()->toArray();
		$detail	= (object)Pages::query()->where('id', 2)->select('header', 'text')->first()->toArray();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'about',
				'og_image'		=> '',
			),
			'headerClass'	=> 'background-2',
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
			'data'			=> $page,
			'detail'		=> $detail,
		];
		
		return view(
			'about',
			$data
		);
	}
}
