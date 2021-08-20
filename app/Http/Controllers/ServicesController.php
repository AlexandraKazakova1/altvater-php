<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Services;
use App\Models\FAQ;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class ServicesController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(Request $request){
		$uri = $request->route('uri');
		
		$page = Services::query()->where('slug', $uri)->where('public', 1)->first();
		
		$detail = (object)Pages::query()->where('id', 2)->select('header', 'text')->first()->toArray();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'services/'.$uri,
				'og_image'		=> '',
			),
			'headerClass'	=> 'background-2',
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
			'data'			=> $page,
			'detail'		=> $detail,
			'faq'			=> FAQ::query()->where('public', 1)->orderBy('sort', 'desc')->select('title', 'text')->get(),
		];
		
		return view(
			'services/once',
			$data
		);
	}
}
