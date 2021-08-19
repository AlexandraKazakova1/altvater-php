<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\News;
use App\Models\Services;
use App\Models\FAQ;
use App\Models\Reviews;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class PageController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$page = (object)Pages::query()->where('slug', 'index')->first()->toArray();
		
		$detail = (object)Pages::query()->where('id', 2)->select('header', 'text')->first()->toArray();
		
		$data = [
			'page'		=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'index',
				'og_image'		=> '',
			),
			'robots'	=> $page->robots,
			'canonical'	=> $page->canonical,
			'data'		=> $page,
			'services'	=> Services::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title', 'image')->get(),
			'news'		=> News::getLast(),
			'reviews'	=> Reviews::query()->where('public', 1)->orderBy('created_at', 'desc')->select('image', 'name', 'text')->get(),
			'faq'		=> FAQ::query()->where('public', 1)->orderBy('sort', 'desc')->select('title', 'text')->get(),
			'detail'	=> $detail,
		];
		
		return view(
			'main',
			$data
		);
	}
	
	public function once(Request $request){
		$uri = $request->route('uri');
		
		$page = Pages::query()->where('slug', $uri)->where('public', 1)->first();
		
		if(!$page){
			return abort(404);
		}
		
		return view(
			'page',
			[
				'page'			=> array(
					'title'			=> $page->title,
					'keywords'		=> $page->keywords,
					'description'	=> $page->description,
					'uri'			=> $uri,
					'og_image'		=> '',
				),
				'data'			=> $page,
				'headerClass'	=> 'small',
				'robots'		=> $page->robots,
				'canonical'		=> $page->canonical,
			]
		);
	}
	
	public function about(){
		$page = Pages::query()->where('slug', 'about')->first();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'about',
				'og_image'		=> '',
			),
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
		];
		
		return view(
			'about',
			$data
		);
	}
}
