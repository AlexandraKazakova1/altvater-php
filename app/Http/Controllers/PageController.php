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

class PageController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$page = (object)Pages::query()->where('slug', 'index')->first()->toArray();
		
		$data = [
			'page' => array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'index',
				'og_image'		=> '',
			),
			'data'	=> $page
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
			return abort_404();
		}
		
		return view(
			'page',
			[
				'page' => array(
					'title'			=> $page->title,
					'keywords'		=> $page->keywords,
					'description'	=> $page->description,
					'uri'			=> $uri,
					'og_image'		=> '',
				),
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
		];
		
		return view(
			'about',
			$data
		);
	}
}
