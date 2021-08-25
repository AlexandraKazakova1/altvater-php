<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;

use App\Http\Requests;

use DB;

use App\Models\SiteMenu;
use App\Models\FooterMenu;

use App\Models\Pages;
use App\Models\News;
use App\Models\Services;

use Illuminate\Support\Facades\View;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Helpers\UseHelper;

class MyController extends Controller {
	
	function __construct(){
		//parent::__construct();
		
		View::share('page', array(
			'title'         => '',
			'keywords'      => '',
			'description'   => '',
			'uri'           => 'index',
			'og_image'   	=> '',
		));
		
		//
		
		$settings = array(
			'appname'			=> '',
			'author'			=> '',
			'map_url'			=> '',
			'copyright'			=> '',
			'google_api_key'	=> '',
			'head_code'			=> '',
			'body_code'			=> '',
			'footer_code'		=> '',
		);
		
		$tmp = DB::table('admin_config')
					->select('name', 'value')
					->whereIn('name', array_keys($settings))
					->get();
		
		if(count($tmp)){
			foreach($tmp as $item){
				$item->value = trim($item->value);
				
				if($item->value != '#'){
					if($item->name == 'copyright'){
						$item->value = str_replace('{Y}', date('Y'), $item->value);
					}
					
					$settings[$item->name] = $item->value;
				}
			}
		}
		
		View::share('settings'				, $settings);
		
		View::share('robots'				, '');
		View::share('canonical'				, '');
		
		View::share('headerClass'			, '');
		
		//
		
		View::share('menu'					, SiteMenu::query()->where('public', 1)->orderBy('sort', 'asc')->get());
		View::share('footer_menu'			, FooterMenu::query()->where('public', 1)->orderBy('sort', 'asc')->get());
		
		View::share('pages'					, Pages::query()->orderBy('title', 'asc')->get());
		
		View::share('styles'				, Config('styles'));
		View::share('scripts'				, Config('scripts'));
		
		View::share('use'					, new UseHelper);
		View::share('image'					, new ImageHelper);
		View::share('string'				, new StringHelper);
	}
}
