<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;

use App\Http\Requests;

use App\Models\SiteMenu;
use App\Models\FooterMenu;

use App\Models\Pages;
use App\Models\News;
use App\Models\Services;
use App\Models\Contacts;

use App\Models\TariffCategory;
use App\Models\CalcObject;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Helpers\UseHelper;

class MyController extends Controller {

	public $_auth	= false;
	public $_user	= [];
	public $_id		= 0;

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
			'header_btn'		=> 0
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
					}else if($item->name == 'header_btn'){
						$item->value = (int)trim($item->value);
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

		View::share('pages'					, Pages::query()->whereNotIn('id', [1, 2, 11])->orderBy('title', 'asc')->get());

		View::share('styles'				, Config('styles'));
		View::share('scripts'				, Config('scripts'));

		View::share('use'					, new UseHelper);
		View::share('image'					, new ImageHelper);
		View::share('string'				, new StringHelper);

		View::share('tariff_category'		, TariffCategory::query()->orderBy('sort', 'asc')->get());
		View::share('calc_object'			, CalcObject::query()->orderBy('sort', 'asc')->get());

		View::share('user'					, []);
		View::share('code'					, '');

		View::share('contacts'				, Contacts::getData());
	}

	function session(){
		$this->_user = Auth::user();

		if($this->_user){
			//$this->_user = (array)$this->_user;

			$this->_id = $this->_user->id;

			$this->_auth = true;
		};

		View::share('user'	, $this->_user);
	}
}
