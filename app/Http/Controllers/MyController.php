<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;

use App\Http\Requests;

use DB;

use App\Models\SiteMenu;
use App\Models\FooterMenu;

use Illuminate\Support\Facades\View;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;

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
			'map_url'			=> '',
			'copyright'			=> '',
		);
		
		$tmp = DB::table('admin_config')
					->select('name', 'value')
					->whereIn('name', array_keys($settings))
					->get();
		
		if(count($tmp)){
			foreach($tmp as $item){
				$settings[$item->name] = trim($item->value);
			}
		}
		
		View::share('settings'		, $settings);
		
		//
		
		View::share('menu'			, SiteMenu::query()->where('public', 1)->orderBy('sort', 'asc')->get());
		View::share('footer_menu'	, FooterMenu::query()->where('public', 1)->orderBy('sort', 'asc')->get());
		
		View::share('imageHelp'		, new ImageHelper);
		View::share('string'		, new StringHelper);
	}
}
