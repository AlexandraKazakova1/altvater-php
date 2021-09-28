<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Services;
use App\Models\ServicesImages;
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
		$page = Pages::query()->where('slug', 'services')->first();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'services',
				'og_image'		=> '',
			),
			'headerClass'	=> 'background-2',
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
			'data'			=> $page,
			'all_services'	=> Services::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title', 'image')->get()
		];
		
		return view(
			'services/index',
			$data
		);
	}
	
	public function once(Request $request){
		$uri = $request->route('uri');
		
		$page = Services::query()->where('slug', $uri)->where('public', 1)->first();
		
		$detail = Pages::query()->where('id', 2)->where('public', 1)->select('header', 'text')->first();
		
		if($detail){
			$detail = (object)$detail->toArray();
		}
		
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
			'images'		=> ServicesImages::query()->where('service_id', $page->id)->select('image', 'alt')->get(),
			'all_services'	=> Services::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title')->get()
		];
		
		return view(
			'services/once',
			$data
		);
	}
}
