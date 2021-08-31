<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Helpers\StringHelper;

use App\Models\Pages;
use App\Models\News;
use App\Models\Services;

use App\Helpers\SiteMapHelper;

class sitemap extends Command {
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:sitemap';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$pages = [];
		
		$tmp = Pages::query()
						->orderBy('title', 'asc')
						->select(
							'id',
							'slug',
							'title',
							'public',
							DB::raw('DATE_FORMAT(`created_at`, "%Y-%m-%d") as `created_at`'),  // 2017-02-05
							DB::raw('DATE_FORMAT(`updated_at`, "%Y-%m-%d") as `updated_at`')
						)
						->get()
						->toArray();
		
		foreach($tmp as $item){
			$item = (object)$item;
			
			if($item->slug == 'index'){
				$item->slug = '/';
				
				$item->changefreq	= 'weekly';
				$item->priority		= 1.0;
			}else if($item->slug == 'news'){
				$item->changefreq	= 'monthly';
				$item->priority		= 0.5;
			}else if($item->slug == 'services'){
				$item->changefreq	= 'monthly';
				$item->priority		= 0.5;
			}else{
				$item->changefreq	= 'weekly';
				$item->priority		= 0.7;
			}
			
			$item->pages = [];
			
			$pages[$item->id] = $item;
		}
		
		$services = Services::query()
							->where('public', 1)
							->orderBy('created_at', 'desc')
							->select(
								'slug',
								'title',
								'public',
								DB::raw('DATE_FORMAT(`created_at`, "%Y-%m-%d") as `created_at`'),  // 2017-02-05
								DB::raw('DATE_FORMAT(`updated_at`, "%Y-%m-%d") as `updated_at`')
							)
							->get()
							->toArray();
		
		foreach($services as $item){
			$item = (object)$item;
			
			$item->changefreq	= 'monthly';
			$item->priority		= 0.8;
			
			$pages[9]->pages[] = $item;
		}
		
		$news = News::query()
						->where('public', 1)
						->orderBy('created_at', 'desc')
						->select(
							'slug',
							'title',
							'public',
							DB::raw('DATE_FORMAT(`created_at`, "%Y-%m-%d") as `created_at`'),  // 2017-02-05
							DB::raw('DATE_FORMAT(`updated_at`, "%Y-%m-%d") as `updated_at`')
						)
						->get()
						->toArray();
		
		foreach($news as $item){
			$item = (object)$item;
			
			$item->changefreq	= 'weekly';
			$item->priority		= 0.7;
			
			$pages[7]->pages[] = $item;
		}
		
		//
		
		$generator = new SiteMapHelper;
		
		foreach($pages as $i => $item){
			$generator->addItem(url($item->slug), (string)($item->updated_at ? $item->updated_at : $item->created_at), $item->changefreq, $item->priority);
			
			if($item->pages){
				foreach($item->pages as $j => $sub){
					$generator->addItem(url($sub->slug), (string)($sub->updated_at ? $sub->updated_at : $sub->created_at), $sub->changefreq, $sub->priority);
				}
			}
		}
		
		$pages = [];
		
		unset($generator);
	}
}
