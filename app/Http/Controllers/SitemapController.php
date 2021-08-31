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

class SitemapController extends MyController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$page = (object)Pages::query()->where('slug', 'sitemap.html')->first()->toArray();
		
		$data = [
			'page'		=> array(
				'title'			=> $page->title,
				'keywords'		=> $page->keywords,
				'description'	=> $page->description,
				'uri'			=> 'sitemap.html',
				'og_image'		=> '',
			),
			'robots'	=> $page->robots,
			'canonical'	=> $page->canonical,
			'data'		=> $page,
			'services'	=> Services::query()->where('public', 1)->orderBy('created_at', 'desc')->select('slug', 'title', 'image')->get(),
			'news'		=> News::getNew()
		];
		
		return view(
			'sitemap',
			$data
		);
	}
	
}
