<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\News;
use App\Models\NewsViews;

use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class NewsController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->session();
		
		$page = (object)Pages::query()->where('slug', 'news')->first()->toArray();
		
		$data = [
			'page'			=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'news',
				'og_image'		=> '',
			),
			'robots'		=> $page->robots,
			'canonical'		=> $page->canonical,
			'data'			=> $page,
			'headerClass'	=> 'background-2',
			'last'			=> News::getLast(),
			'popular'		=> News::getPopular(),
			'news'			=> News::getAll(),
			'all_news'		=> News::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title')->get()
		];
		
		return view(
			'news/index',
			$data
		);
	}
	
	public function once(Request $request){
		$this->session();
		
		$uri = $request->route('uri');
		
		$page = News::query()->where('slug', $uri)->where('public', 1)->first();
		
		if(!$page){
			return abort(404);
		}
		
		$ip = \ip2long($request->ip());
		
		$check = NewsViews::query()->where('page_id', $page->id)->where('ip', $ip)->first();
		
		if(!$check){
			NewsViews::insert([
				'page_id'	=> $page->id,
				'ip'		=> $ip
			]);
			
			News::query()->where('id', $page->id)->update([
				'views'	=> ($page->views + 1)
			]);
		}
		
		return view(
			'news/once',
			[
				'page'			=> array(
					'title'			=> $page->title,
					'keywords'		=> $page->keywords,
					'description'	=> $page->description,
					'uri'			=> 'news/'.$uri,
					'og_image'		=> '',
				),
				'headerClass'	=> 'background-2',
				'data'			=> $page,
				'robots'		=> $page->robots,
				'canonical'		=> $page->canonical,
				'news'			=> News::getNew(),
				'all_news'		=> News::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title')->get()
			]
		);
	}
}
