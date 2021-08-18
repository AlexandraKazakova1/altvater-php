<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Helpers\CurlHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Helpers\GeocodeHelper;

use App\Models\Contents;
use App\Models\Clients;
use App\Models\Contracts;
use App\Models\Payments;
use App\Models\Packages;
use App\Models\Applications;
use App\Models\ApplicationsPhotos;
use App\Models\Actions;
use App\Models\Violation;
use App\Models\ViolationPhotos;
use App\Models\Reviews;
use App\Models\SaleContainers;
use App\Models\Purchase;
use App\Models\EmailTemplates;

use Viber\Bot;
use Viber\Api\Sender;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ViberController extends Controller {
	
	public function __construct(){
		//parent::__construct();
	}
	
	public function homeKeyboard(){
		$rows		= [];
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('conclude-an-agreement')
						->setText('Заключити договір');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('pay')
						->setText('Сплатити');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('to-take-out')
						->setText('Треба вивезти');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('sale')
						->setText('Продаж контейнерів');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('purchase')
						->setText('Закупівля вторинних ресурсів');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('review')
						->setText('Залишити відгук про чат-бота');
		
		return $rows;
	}
	
	public function contractKeyboard(){
		$rows		= [];
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('contract-household')
						->setText('Побутові');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('contract-construction')
						->setText('Будівельні/великогабаритні');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('contract-separate')
						->setText('Роздільне збирання');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('home')
						->setText('До головної');
		
		return $rows;
	}
	
	public function payKeyboard(){
		$rows		= [];
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('pay-juridical')
						->setText('Юридична особа');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('pay-physical')
						->setText('Фізична особа (приватний сектор)');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('home')
						->setText('До головної');
		
		return $rows;
	}
	
	public function toTakeOutKeyboard(){
		$rows		= [];
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('violation-schedule')
						->setText('Порушення графіку?');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('application')
						->setText('Заявка (додатково)');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('home')
						->setText('До головної');
		
		return $rows;
	}
	
	public function applicationKeyboard(){
		$rows		= [];
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('application-household')
						->setText('Побутові');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						->setActionType('reply')
						->setActionBody('application-separate')
						->setText('Роздільно зібрані');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(6)
						//->setRows(2)
						->setActionType('reply')
						->setActionBody('app-construction')
						->setText('Будівельні/великогабаритні (контейнер)');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('to-take-out')
						->setText('Назад');
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
						->setColumns(3)
						->setActionType('reply')
						->setActionBody('home')
						->setText('До головної');
		
		return $rows;
	}
	
	public function ratingKeyboard(){
		$rows		= [];
		
		for($i = 1; $i < 6; $i++){
			$rows[] = (new \Viber\Api\Keyboard\Button())
							->setColumns(2)
							->setActionType('reply')
							->setActionBody("review-".$i)
							->setText($i);
		}
		
		$rows[] = (new \Viber\Api\Keyboard\Button())
							->setColumns(6)
							->setActionType('reply')
							->setActionBody('home')
							->setText('До головної');
		
		return $rows;
	}
	
	protected function getLocation($lat, $lng){
		$key = env('GEOCODE_KEY', '');
		
		if(!$key){
			return false;
		}
		
		$helper = new GeocodeHelper;
		$helper->setKey($key);
		
		$helper->language	= 'uk';
		$helper->address	= true;
		$helper->city		= true;
		$helper->area		= true;
		$helper->street		= true;
		$helper->house		= true;
		
		$result = $helper->query(null, $lat.",".$lng);
		
		if($result){
			if($result->address){
				$address = [];
				
				if($result->city){
					$address[] = $result->city;
				}
				
				if($result->street){
					$address[] = $result->street;
				}
				
				if($result->house){
					$address[] = $result->house;
				}
				
				return implode(', ', $address);
			}
		}
		
		return "";
	}
	
	protected function download($url, $dir_name){
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(30);
		
		$dir = STOR_PATH."/app/admin";
		
		if(!is_dir($dir)){
			mkdir($dir);
		}
		
		$dir = $dir."/".$dir_name;
		
		if(!is_dir($dir)){
			mkdir($dir);
		}
		
		$filename = md5(time()."-".mt_rand(10, 99)).".jpg";
		
		if(CurlHelper::save($dir."/".$filename)){
		//if(file_put_contents($filename, file_get_contents($url))){
			return $dir_name."/".$filename;
		}
		
		return "";
	}
	
	protected function notificationContract($id){
		$contract = Contracts::query()->where('id', $id)->first();
		
		if(!$contract){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'contract-'.$contract->type)->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':type',
				':container',
				':volume',
				':count',
				':period',
				':address',
				':name',
				':phone',
				':email',
				':detail'
			],
			[
				trans('admin.contracts.type_'.$contract->type),
				trans('admin.contracts.container_'.$contract->container),
				$contract->volume,
				$contract->count_containers,
				$contract->period,
				$contract->address,
				$contract->name,
				$contract->phone,
				$contract->email,
				url('/contracts-'.$contract->type.'/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	protected function notificationApplication($id){
		$app = Applications::query()->where('id', $id)->first();
		
		if(!$app){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'app-'.$app->type)->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':type',
				':service',
				':volume',
				':address',
				':name',
				':phone',
				':email',
				':detail'
			],
			[
				trans('admin.applications.type_'.$app->type),
				trans('admin.applications.service_'.$app->service),
				$app->volume,
				$app->address,
				$app->name,
				$app->phone,
				$app->email,
				url('/applications/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$file = '';
		
		$img = ApplicationsPhotos::query()->where('record_id', $id)->first();
		
		if($img){
			$dir = STOR_PATH."/app/admin/";
			
			$file = $dir.$img->image;
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	protected function notificationViolation($id){
		$record = Violation::query()->where('id', $id)->first();
		
		if(!$record){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'violation-schedule')->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':type',
				':address',
				':name',
				':detail'
			],
			[
				trans('admin.violation.type_'.$record->type),
				$record->address,
				$record->name,
				url('/violation-schedule/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$file = '';
		
		$img = ViolationPhotos::query()->where('record_id', $id)->first();
		
		if($img){
			$dir = STOR_PATH."/app/admin/";
			
			$file = $dir.$img->image;
		}
		
		$this->sendEmail($template->subject, $text, $to, $file);
	}
	
	protected function notificationPay($id){
		$record = Payments::query()->where('id', $id)->where('type', 'juridical')->first();
		
		if(!$record){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'pay-'.$record->type)->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':type',
				':address',
				':name',
				':email',
				':count',
				':amount',
				':detail'
			],
			[
				trans('admin.payments.type_'.$record->type),
				$record->address,
				$record->name,
				$record->email,
				$record->count_packages,
				$record->amount,
				url('/payments/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	protected function notificationReview($id, $client){
		$record = Reviews::query()->where('id', $id)->first();
		
		if(!$record){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'review')->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':name',
				':rating',
				':text',
				':detail'
			],
			[
				$client->name,
				$record->rating,
				$record->text,
				url('/reviews/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	protected function notificationSale($id){
		$record = SaleContainers::query()->where('id', $id)->first();
		
		if(!$record){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'sale_containers')->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':color',
				':value',
				':count',
				':email',
				':phone',
				':name',
				':detail'
			],
			[
				trans('admin.sale_containers.'.$record->color),
				$record->value,
				$record->count,
				$record->email,
				$record->phone,
				$record->name,
				url('/sale-containers/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	protected function notificationPurchase($id){
		$record = Purchase::query()->where('id', $id)->first();
		
		if(!$record){
			return false;
		}
		
		$template = EmailTemplates::query()->where('slug', 'purchase')->first();
		
		if(!$template){
			return false;
		}
		
		$text = str_replace(
			[
				':type',
				':phone',
				':email',
				':name',
				':weight',
				':address',
				':detail'
			],
			[
				trans('admin.purchase.'.$record->type),
				$record->phone,
				$record->email,
				$record->name,
				$record->weight,
				$record->address,
				url('/purchase/'.$id.'/edit')
			],
			$template->content
		);
		
		$to = [];
		
		$emails = trim($template->emails);
		$emails = explode(',', $emails);
		
		foreach($emails as $item){
			$item = trim($item);
			
			if($item){
				$to[] = $item;
			}
		}
		
		$this->sendEmail($template->subject, $text, $to);
	}
	
	public function index(Request $request){
		$key = env('VIBER_KEY', '');
		
		if(!$key){
			return false;
		}
		
		// log bot interaction
		//$log = new Logger('bot');
		//$log->pushHandler(new StreamHandler(LOGS_PATH.'/bot.log'));
		
		if(!file_exists(LOGS_PATH.'/bot.log')){
			file_put_contents(LOGS_PATH.'/bot.log', '');
		}
		
		$botSender = new Sender([
			'name'		=> env('VIBER_NAME', ''),
			'avatar'	=> url('/favicon.ico'),
		]);
		
		$currentClass = $this;
		
		try{
			$bot = new Bot(['token' => $key]);
			
			$homeKeyboard			= $this->homeKeyboard();
			$contractKeyboard		= $this->contractKeyboard();
			$payKeyboard			= $this->payKeyboard();
			$toTakeOutKeyboard		= $this->toTakeOutKeyboard();
			$applicationKeyboard	= $this->applicationKeyboard();
			$ratingKeyboard			= $this->ratingKeyboard();
			
			$bot->onConversation(function($event) use ($bot, $botSender, $homeKeyboard, $currentClass){
				$client = Clients::query()->where('viber_id', $event->getUser()->getId())->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$data = Contents::query()->where('id', 1)->first();
				
				if($data && $data->text){
					return (new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setText($data->text)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($homeKeyboard)
						);
				}
			})
			->onSubscribe(function($event) use ($bot, $botSender, $currentClass){
				$data = Contents::query()->where('id', 9)->first();
				
				if($data && $data->text){
					return (new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setText($data->text);
				}
			})
			->onPicture(function($event) use ($bot, $botSender, $currentClass){
				$message	= $event->getMessage();
				
				$receiverId	= $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$tracking	= $event->getMessage()->getTrackingData();
				
				if($tracking){
					$tracking = json_decode($tracking, true);
					
					if(isset($tracking['current-action'])){
						if($tracking['action'] == 'violation-schedule'){
							$rows = [];
							
							$file = $this->download($message->getMedia(), 'violation-images');
							
							if($file){
								$answer	= "Успішно";
								
								$record = Violation::create([
									'client_id'			=> $client->id,
									'type'				=> $tracking['type'],
									'address'			=> $tracking['address'],
									'name'				=> $tracking['name']
								]);
								
								ViolationPhotos::create([
									'record_id'			=> $record->id,
									'image'				=> $file
								]);
								
								$currentClass->notificationViolation($record->id);
								
								$tracking = [];
								
								$columns = 6;
								
								$data = Contents::query()->where('id', 7)->first();
								
								if($data){
									$answer = $data->text;
								}
							}else{
								$answer	= "Сталась помилка, відправте фото повторно";
							}
							
							$rows[] = (new \Viber\Api\Keyboard\Button())
											->setColumns($columns)
											->setActionType('reply')
											->setActionBody('home')
											->setText('До головної');
							
							$bot->getClient()->sendMessage(
									(new \Viber\Api\Message\Text())
									->setSender($botSender)
									->setReceiver($receiverId)
									->setText($answer)
									->setKeyboard(
										(new \Viber\Api\Keyboard())->setButtons($rows)
									)
								);
							
							return false;
						}
						
						if($tracking['action'] == 'application' && $tracking['current-action'] == 'get-photo'){
							$rows = [];
							
							$file = $this->download($message->getMedia(), 'applications-images');
							
							if($file){
								$answer	= "Успішно";
								
								$record = Applications::create([
									'client_id'			=> $client->id,
									'type'				=> $tracking['type'],
									'address'			=> $tracking['address'],
									'name'				=> $tracking['name']
								]);
								
								ApplicationsPhotos::create([
									'record_id'			=> $record->id,
									'image'				=> $file
								]);
								
								$currentClass->notificationApplication($record->id);
								
								$tracking = [];
								
								$columns = 6;
								
								$data = Contents::query()->where('id', 7)->first();
								
								if($data){
									$answer = $data->text;
								}
							}else{
								$answer	= "Сталась помилка, відправте фото повторно";
							}
							
							$rows[] = (new \Viber\Api\Keyboard\Button())
											->setColumns($columns)
											->setActionType('reply')
											->setActionBody('home')
											->setText('До головної');
							
							$bot->getClient()->sendMessage(
									(new \Viber\Api\Message\Text())
									->setSender($botSender)
									->setReceiver($receiverId)
									->setText($answer)
									->setKeyboard(
										(new \Viber\Api\Keyboard())->setButtons($rows)
									)
								);
							
							return false;
						}
					}
				}
			})
			->onText('|^sale$|'										, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$tracking	= [];
				
				$tracking['action']			= 'sale';
				$tracking['current-action']	= 'get-value';
				$tracking['back']			= 'home';
				
				$rows = [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-120')
								->setText('120 літрів');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-240')
								->setText('240 літрів');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-1100')
								->setText('1100 літрів');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-value-container'))
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^sale-([0-9]*)$|'							, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				
				$args = array_values($args);
				
				$value = $args[0];
				
				$tracking	= [];
				
				$tracking['action']			= 'sale';
				$tracking['value']			= $value;
				$tracking['current-action']	= 'get-color';
				
				$answer = "Оберіть колір контейнера ⤵️";
				//$answer = "Вкажіть бажану кількість, шт. ⤵️";
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-'.$value.'-gray')
								->setText('Темно-сірий');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-'.$value.'-green')
								->setText('Зелений');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('sale-'.$value.'-blue')
								->setText('Синій');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('sale')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^sale-([0-9]*)-([a-z]*)$|'					, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				
				$args = array_values($args);
				
				$tracking	= [];
				
				$tracking['action']			= 'sale';
				$tracking['value']			= $args[0];
				$tracking['color']			= $args[1];
				$tracking['current-action']	= 'get-count';
				
				$answer = "Вкажіть бажану кількість, шт. ⤵️";
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('sale-'.$args[0])
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^purchase$|'									, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$tracking	= [];
				
				$tracking['action']			= 'purchase';
				$tracking['current-action']	= 'get-type';
				$tracking['back']			= 'home';
				
				$rows = [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('purchase-wastepaper')
								->setText('Макулатура');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('purchase-pet_bottle')
								->setText('ПЕТ-пляшка');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('purchase-pet_film')
								->setText('ПЕТ-плівка');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('purchase-metal')
								->setText('Метал');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('purchase-glass')
								->setText('Скло');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-sale-type'))
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^purchase-([a-z_]*)$|'						, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				
				$args = array_values($args);
				
				$value = $args[0];
				
				$tracking	= [];
				
				$tracking['action']			= 'purchase';
				$tracking['type']			= $value;
				$tracking['current-action']	= 'get-address';
				
				$answer = "Вкажіть адресу накопичення ⤵️";
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('sale')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^home$|'										, function ($event) use ($bot, $botSender, $currentClass, $homeKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$data = Contents::query()->where('id', 1)->first();
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($data->text)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($homeKeyboard)
						)
					);
			})
			->onText('|^conclude-an-agreement$|'					, function ($event) use ($bot, $botSender, $currentClass, $contractKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-garbage-type'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($contractKeyboard)
						)
					);
			})
			->onText('|^pay$|'										, function ($event) use ($bot, $botSender, $currentClass, $payKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-action'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($payKeyboard)
						)
					);
			})
			->onText('|^to-take-out$|'								, function ($event) use ($bot, $botSender, $currentClass, $toTakeOutKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-action'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($toTakeOutKeyboard)
						)
					);
			})
			->onText('|^violation-schedule$|'						, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('violation-schedule-household')
								->setText('Побутові');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('violation-schedule-separate')
								->setText('Роздільно зібрані');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('application')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-garbage-type'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^violation-schedule-([a-z0-9\-_\.]*)$|'		, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				$tracking	= [];
				
				$count = count($args);
				
				$back = "violation-schedule";
				
				$answer		= "⤵️";
				
				if($count == 1){
					$tracking	= $event->getMessage()->getTrackingData();
					
					if($tracking){
						$tracking	= json_decode($tracking, true);
					}else{
						$tracking	= [];
					}
					
					$tracking['action']			= 'violation-schedule';
					$tracking['type']			= $args[0];
					
					$tracking['back']			= $back."-".$args[0];
					
					$answer		= "Адреса обслуговування ⤵️";
					
					$tracking['current-action']	= 'get-address';
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('location-picker')
								->setActionBody('location-picker')
								->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
				}elseif($count == 2){
					$tracking	= $event->getMessage()->getTrackingData();
					
					if($tracking){
						$tracking	= json_decode($tracking, true);
					}else{
						$tracking	= [];
					}
					
					Actions::query()->where('client_id', $client->id)->delete();
					
					$tracking	= $event->getMessage()->getTrackingData();
					$tracking	= json_decode($tracking, true);
					
					$answer	= "Успішно";
					
					$record = Violation::create([
						'client_id'			=> $client->id,
						'type'				=> $tracking['type'],
						'address'			=> $tracking['address'],
						'name'				=> $tracking['name']
					]);
					
					$currentClass->notificationViolation($record->id);
					
					$columns = 6;
					
					$data = Contents::query()->where('id', 7)->first();
					
					if($data){
						$answer = $data->text;
					}
					
					$tracking	= [];
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($back)
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^application$|'								, function ($event) use ($bot, $botSender, $currentClass, $applicationKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-type'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($applicationKeyboard)
						)
					);
			})
			->onText('|^application-([a-z0-9\-_\.]*)$|'				, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				
				$args = array_values($args);
				
				$count = count($args);
				
				$back = "application";
				
				$tracking = [];
				
				$tracking['action']				= 'application';
				$tracking['type']				= $args[0];
				$tracking['back']				= $back;
				
				$columns = 3;
				
				if($count == 1){
					$tracking['current-action']		= 'get-address';
					
					$rows		= [];
					
					$answer		= "Адреса обслуговування ⤵️";
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
							->setColumns(6)
							->setActionType('location-picker')
							->setActionBody('location-picker')
							->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($tracking['back'])
									->setText('Назад');
				}elseif($count == 2){
					Actions::query()->where('client_id', $client->id)->delete();
					
					$tracking	= $event->getMessage()->getTrackingData();
					$tracking	= json_decode($tracking, true);
					
					$answer	= "Успішно";
					
					$record = Applications::create([
						'client_id'			=> $client->id,
						'type'				=> $tracking['type'],
						'address'			=> $tracking['address'],
						'name'				=> $tracking['name']
					]);
					
					$currentClass->notificationApplication($record->id);
					
					$columns = 6;
					
					$id = 7;
					
					if($args[0] == 'household' || $args[0] == 'separate'){
						$id = 10;
					}
					
					$data = Contents::query()->where('id', $id)->first();
					
					if($data){
						$answer = $data->text;
					}
					
					$tracking	= [];
				}else{
					$answer	= trans('bot.select-action');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setMinApiVersion(7)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^app-construction$|'							, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-install')
								->setText('Встановити');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-replace')
								->setText('Замінити');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('app-construction-withdraw')
								->setText('Зняти');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('application')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-action'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^app-construction-([a-z0-9\-_\.]*)$|'		, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				$tracking	= [];
				
				$count = count($args);
				
				$back = "app-construction";
				
				$answer		= "⤵️";
				
				$tracking = [];
				
				if($count == 1){
					$answer		= "Об'єм контейнера ⤵️";
					
					$tracking['action']				= 'app';
					$tracking['type']				= 'construction';
					$tracking['container_action']	= $args[0];
					$tracking['back']				= $back;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-'.$args[0].'-8_11')
								->setText('8-11');
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-'.$args[0].'-15_17')
								->setText('15-17');
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-'.$args[0].'-23_25')
								->setText('23-25');
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('app-construction-'.$args[0].'-30_35')
								->setText('30-35');
				}elseif($count == 2){
					$tracking	= $event->getMessage()->getTrackingData();
					$tracking = json_decode($tracking, true);
					
					$tracking['volume']			= str_replace('_', '-', $args[1]);
					$tracking['back']			= $tracking['back']."-".$args[1];
					
					$answer		= "Адреса обслуговування ⤵️";
					
					$tracking['current-action']	= 'get-address';
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
							->setColumns(6)
							->setActionType('location-picker')
							->setActionBody('location-picker')
							->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($back)
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-household$|'						, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-household-in_stock')
								->setText('Є контейнер');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-household-for_rent')
								->setText('Контейнер в оренду');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('reply')
								->setActionBody('contract-household-to_buy')
								->setText('Контейнер купити');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('conclude-an-agreement')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.is-the-container'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-household-([a-z0-9\-_\.]*)$|'		, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				$tracking	= [];
				
				$count = count($args);
				
				$back = "contract-household";
				
				$answer		= "⤵️";
				
				if($count == 1){
					$answer		= "Оберіть контейнер ⤵️";
					
					$tracking['action']			= 'contract';
					$tracking['type']			= 'household';
					$tracking['availability']	= $args[0];
					$tracking['back']			= $back;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-household-'.$args[0].'-min')
								->setText('менше 1,1 м.куб.');
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-household-'.$args[0].'-1.1')
								->setText('1,1 м.куб.');
				}elseif($count == 2){
					$answer		= "Скільки штук? ⤵️";
					
					$back .= "-".$args[0];
					
					$tracking['action']			= 'contract';
					$tracking['type']			= 'household';
					$tracking['availability']	= $args[0];
					$tracking['container']		= $args[1];
					$tracking['current-action']	= 'get-count';
					$tracking['back']			= $back;
				}elseif($count == 3){
					$tracking	= $event->getMessage()->getTrackingData();
					$tracking = json_decode($tracking, true);
					
					$tracking['period']			= $args[2];
					$tracking['back']			= $tracking['back']."-".$args[2];
					
					$answer		= "Адреса обслуговування ⤵️";
					
					$tracking['current-action']	= 'get-address';
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('location-picker')
								->setActionBody('location-picker')
								->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($back)
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-construction$|'					, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-construction-8_11')
								->setText('8-11');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-construction-15_17')
								->setText('15-17');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-construction-23_25')
								->setText('23-25');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('contract-construction-30_35')
								->setText('30-35');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('conclude-an-agreement')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.select-container-volume'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-construction-([a-z0-9\-_\.]*)$|'	, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				$tracking	= [];
				
				$count = count($args);
				
				$back = "contract-construction";
				
				$answer		= "⤵️";
				
				if($count == 1){
					$answer		= "Адреса обслуговування ⤵️";
					
					$tracking['action']			= 'contract';
					$tracking['type']			= 'construction';
					$tracking['volume']			= $args[0];
					$tracking['current-action']	= 'get-address';
					$tracking['back']			= $back;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('location-picker')
								->setActionBody('location-picker')
								->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($back)
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-separate$|'						, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('conclude-an-agreement')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$tracking	= [];
				
				$tracking['action']			= 'contract';
				$tracking['type']			= 'separate';
				$tracking['current-action']	= 'get-count';
				$tracking['back']			= 'conclude-an-agreement';
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.count-container'))
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^contract-separate-([a-z0-9\-_\.]*)$|'		, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				$tracking	= [];
				
				$count = count($args);
				
				$back = "contract-separate";
				
				$answer		= "⤵️";
				
				if($count == 2){
					$answer		= "Адреса обслуговування ⤵️";
					
					$tracking['action']			= 'contract';
					$tracking['type']			= 'separate';
					$tracking['count']			= $args[0];
					$tracking['period']			= $args[1];
					$tracking['current-action']	= 'get-address';
					$tracking['back']			= $back;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(6)
								->setActionType('location-picker')
								->setActionBody('location-picker')
								->setText('Відправити поточну локацію');
					
					if(false){
						Actions::query()->where('client_id', $client->id)->delete();
						
						Actions::insert([
							'id'		=> md5($client->id.'-'.time()),
							'client_id'	=> $client->id,
							'tracking'	=> json_encode($tracking)
						]);
					}
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($back)
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^pay-juridical$|'							, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('pay')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$tracking	= [];
				
				$tracking['action']			= 'pay';
				$tracking['type']			= 'juridical';
				$tracking['current-action']	= 'get-name';
				$tracking['back']			= 'pay-juridical';
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.enter-name'))
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^pay-juridical-([a-z0-9\-_\.]*)$|'			, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				$text		= "";
				
				$tracking	= $event->getMessage()->getTrackingData();
				$tracking	= json_decode($tracking, true);
				
				$count = count($args);
				
				$answer		= "⤵️";
				
				if($count == 2){
					$answer		= "email ⤵️";
					
					//$tracking['action']		= 'pay';
					//$tracking['type']			= 'juridical';
					//$tracking['name']			= $args[0];
					$tracking['current-action']	= 'get-email';
					//$tracking['back']			= $back;
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($tracking['back'])
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns($columns)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^pay-physical$|'								, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
							->setColumns(6)
							->setActionType('location-picker')
							->setActionBody('location-picker')
							->setText('Відправити поточну локацію');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('pay')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$tracking	= [];
				
				$tracking['action']			= 'pay';
				$tracking['type']			= 'physical';
				$tracking['current-action']	= 'get-address';
				$tracking['back']			= 'pay-physical';
				
				if(false){
					Actions::query()->where('client_id', $client->id)->delete();
					
					Actions::insert([
						'id'		=> md5($client->id.'-'.time()),
						'client_id'	=> $client->id,
						'tracking'	=> json_encode($tracking)
					]);
				}
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.enter-address'))
						->setTrackingData(json_encode($tracking))
						->setMinApiVersion(7)
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^pay-physical-([a-z0-9\-_\.]*)$|'			, function ($event) use ($bot, $botSender, $currentClass) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				unset($args[1]);
				
				$args = array_values($args);
				
				$rows		= [];
				
				$tracking	= $event->getMessage()->getTrackingData();
				$tracking	= json_decode($tracking, true);
				
				$count = count($args);
				
				$answer		= "⤵️";
				
				if($count == 3){
					$id = (int)$args[2];
					
					$pkg = Packages::query()->where('id', $id)->first();
					
					$tracking['price']			= $pkg->price;
					$tracking['package_id']		= $pkg->id;
					$tracking['current-action']	= 'get-count';
					
					$answer = "Яку кількість? ⤵️";
				}
				
				$columns = 6;
				
				if(false){
					$columns = 3;
					
					$rows[] = (new \Viber\Api\Keyboard\Button())
									->setColumns(3)
									->setActionType('reply')
									->setActionBody($tracking['back'])
									->setText('Назад');
				}
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->onText('|^review$|'									, function ($event) use ($bot, $botSender, $currentClass, $ratingKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText(trans('bot.set-rating'))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($ratingKeyboard)
						)
					);
			})
			->onText('|^review-([0-9]*)$|'							, function ($event) use ($bot, $botSender, $currentClass, $ratingKeyboard) {
				$receiverId = $event->getSender()->getId();
				
				$client = Clients::query()->where('viber_id', $receiverId)->first();
				
				if(!$client){
					$client = Clients::create([
						'name'		=> $event->getUser()->getName(),
						'viber_id'	=> $event->getUser()->getId()
					]);
				}
				
				$client->blocked = (int)$client->blocked;
				
				if($client->blocked > 0){
					return false;
				}
				
				$text = $event->getMessage()->getText();
				
				$args = explode('-', $text);
				
				unset($args[0]);
				
				$args = array_values($args);
				
				$rating = $args[0];
				
				$tracking	= [];
				
				$tracking['action']			= 'review';
				$tracking['rating']			= $rating;
				$tracking['current-action']	= 'get-text';
				
				$answer = "Дякуємо! Напишіть, будь ласка, що саме сподобалось, а що ні. ⤵️";
				
				$rows		= [];
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('review')
								->setText('Назад');
				
				$rows[] = (new \Viber\Api\Keyboard\Button())
								->setColumns(3)
								->setActionType('reply')
								->setActionBody('home')
								->setText('До головної');
				
				$bot->getClient()->sendMessage(
						(new \Viber\Api\Message\Text())
						->setSender($botSender)
						->setReceiver($receiverId)
						->setText($answer)
						->setTrackingData(json_encode($tracking))
						->setKeyboard(
							(new \Viber\Api\Keyboard())->setButtons($rows)
						)
					);
			})
			->on(function($event){
				return true; // match all
			}, function ($event) use ($bot, $botSender, $currentClass) {
				if($event->getEventType() == "unsubscribed"){
					return false;
				}
				
				if($event->getEventType() == "message"){
					$message	= $event->getMessage();
					$class		= \get_class($message);
					
					$receiverId	= $event->getSender()->getId();
					
					$client = Clients::query()->where('viber_id', $receiverId)->first();
					
					if(!$client){
						$client = Clients::create([
							'name'		=> $event->getUser()->getName(),
							'viber_id'	=> $event->getUser()->getId()
						]);
					}
					
					$client->blocked = (int)$client->blocked;
					
					if($client->blocked > 0){
						return false;
					}
					
					$text		= "";
					$tracking	= "";
					$phone		= "";
					$location	= [];
					
					if($class == "Viber\Api\Message\Text"){
						$text		= $event->getMessage()->getText();
					}
					
					$tracking	= $event->getMessage()->getTrackingData();
					
					$action = Actions::query()->where('client_id', $client->id)->first();
					
					if(!$tracking){
						if($action){
							$tracking = $action->tracking;
						}
					}
					
					if($action){
						$action->delete();
					}
					
					if($class == "Viber\Api\Message\Contact"){
						$phone		= $event->getMessage()->getPhoneNumber();
					}
					
					if($class == "Viber\Api\Message\Location"){
						$location	= $event->getMessage()->getLocation();
					}
					
					if($tracking){
						$tracking = json_decode($tracking, true);
						
						if(isset($tracking['current-action'])){
							if($tracking['action'] == 'contract' && $tracking['type'] == 'household'){
								if($tracking['current-action'] == 'get-count'){
									$text = (int)trim($text);
									
									$rows = [];
									
									if($text < 1 || $text > 50){
										$answer = "Кількість вказано некоректно. Скільки штук? ⤵️";
									}else{
										unset($tracking['current-action']);
										
										$answer = "Як часто вивозити, разів/тиждень? ⤵️";
										
										$tracking['count']	= $text;
										$tracking['back']	= $tracking['back']."-".$text;
										
										for($i = 1; $i < 7; $i++){
											$rows[] = (new \Viber\Api\Keyboard\Button())
															->setColumns(1)
															->setActionType('reply')
															->setActionBody($tracking['back']."-".$i)
															->setText($i);
										}
										
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "тел./email ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-contact';
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('share-phone')
													->setActionBody('share-phone')
													->setText('Відправити номер телефону');
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setMinApiVersion(7)
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										if($tracking['container'] == 'min'){
											$tracking['container'] = '<1.1';
										}
										
										$record = Contracts::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'container'			=> str_replace('_', '-', $tracking['availability']),
											'volume'			=> $tracking['container'],
											'count_containers'	=> $tracking['count'],
											'period'			=> $tracking['period'],
											'address'			=> $tracking['address'],
											'name'				=> $tracking['name'],
											'phone'				=> $phone
										]);
										
										$currentClass->notificationContract($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 4)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											if($tracking['container'] == 'min'){
												$tracking['container'] = '<1.1';
											}
											
											$record = Contracts::create([
												'client_id'			=> $client->id,
												'type'				=> $tracking['type'],
												'container'			=> str_replace('_', '-', $tracking['availability']),
												'volume'			=> $tracking['container'],
												'count_containers'	=> $tracking['count'],
												'period'			=> $tracking['period'],
												'address'			=> $tracking['address'],
												'name'				=> $tracking['name'],
												'email'				=> $text,
											]);
											
											$currentClass->notificationContract($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 4)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
												
												$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
											}else{
												if($tracking['container'] == 'min'){
													$tracking['container'] = '<1.1';
												}
												
												$record = Contracts::create([
													'client_id'			=> $client->id,
													'type'				=> $tracking['type'],
													'container'			=> str_replace('_', '-', $tracking['availability']),
													'volume'			=> $tracking['container'],
													'count_containers'	=> $tracking['count'],
													'period'			=> $tracking['period'],
													'address'			=> $tracking['address'],
													'name'				=> $tracking['name'],
													'phone'				=> $text,
												]);
												
												$currentClass->notificationContract($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 4)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'contract' && $tracking['type'] == 'construction'){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "тел./email ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-contact';
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('share-phone')
													->setActionBody('share-phone')
													->setText('Відправити номер телефону');
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setMinApiVersion(7)
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										$record = Contracts::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'volume'			=> str_replace('_', '-', $tracking['volume']),
											'address'			=> $tracking['address'],
											'name'				=> $tracking['name'],
											'phone'				=> $phone
										]);
										
										$currentClass->notificationContract($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 4)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											$record = Contracts::create([
												'client_id'			=> $client->id,
												'type'				=> $tracking['type'],
												'volume'			=> str_replace('_', '-', $tracking['volume']),
												'address'			=> $tracking['address'],
												'name'				=> $tracking['name'],
												'email'				=> $text,
											]);
											
											$currentClass->notificationContract($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 4)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
												
												$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
											}else{
												$record = Contracts::create([
													'client_id'			=> $client->id,
													'type'				=> $tracking['type'],
													'volume'			=> str_replace('_', '-', $tracking['volume']),
													'address'			=> $tracking['address'],
													'name'				=> $tracking['name'],
													'phone'				=> $text,
												]);
												
												$currentClass->notificationContract($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 4)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'contract' && $tracking['type'] == 'separate'){
								if($tracking['current-action'] == 'get-count'){
									$text = (int)trim($text);
									
									$rows = [];
									
									if($text < 1 || $text > 50){
										$answer = "Кількість вказано некоректно. Вкажіть кількість ⤵️";
									}else{
										unset($tracking['current-action']);
										
										$answer = "Як часто вивозити, разів/тиждень? ⤵️";
										
										$tracking['count']	= $text;
										$tracking['back']	= $tracking['action']."-".$tracking['type']."-".$text;
										
										for($i = 1; $i < 7; $i++){
											$rows[] = (new \Viber\Api\Keyboard\Button())
															->setColumns(1)
															->setActionType('reply')
															->setActionBody($tracking['back']."-".$i)
															->setText($i);
										}
										
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "тел./email ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-contact';
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('share-phone')
													->setActionBody('share-phone')
													->setText('Відправити номер телефону');
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setMinApiVersion(7)
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										$record = Contracts::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'count_containers'	=> $tracking['count'],
											'period'			=> $tracking['period'],
											'address'			=> $tracking['address'],
											'name'				=> $tracking['name'],
											'phone'				=> $phone
										]);
										
										$currentClass->notificationContract($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 4)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											$record = Contracts::create([
												'client_id'			=> $client->id,
												'type'				=> $tracking['type'],
												'count_containers'	=> $tracking['count'],
												'period'			=> $tracking['period'],
												'address'			=> $tracking['address'],
												'name'				=> $tracking['name'],
												'email'				=> $text,
											]);
											
											$currentClass->notificationContract($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 4)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
											}else{
												$record = Contracts::create([
													'client_id'			=> $client->id,
													'type'				=> $tracking['type'],
													'count_containers'	=> $tracking['count'],
													'period'			=> $tracking['period'],
													'address'			=> $tracking['address'],
													'name'				=> $tracking['name'],
													'phone'				=> $text,
												]);
												
												$currentClass->notificationContract($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 4)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'pay' && $tracking['type'] == 'juridical'){
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "Оберіть дію ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										unset($tracking['current-action']);
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('reply')
													->setActionBody($tracking['back']."-amount")
													->setText('Дізнатись сумму до сплати');
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-email'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if(filter_var($text, FILTER_VALIDATE_EMAIL)){
										$record = Payments::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'name'				=> $tracking['name'],
											'email'				=> $text,
										]);
										
										$currentClass->notificationPay($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 5)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										$answer	= "Email вказано некоректно";
										
										if(false){
											$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(3)
													->setActionType('reply')
													->setActionBody($tracking['back'])
													->setText('Назад');
										}else{
											$columns = 6;
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'pay' && $tracking['type'] == 'physical'){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ПІБ ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\-, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "Дізнатись вартість спец пакетів (послуга) ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-price';
										
										$pkgs = Packages::query()->where('public', 1)->orderBy('sort', 'asc')->get();
										
										foreach($pkgs as $item){
											$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back']."-".$item->id)
														->setText($item->name);
										}
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-count'){
									$text = (int)trim($text);
									
									$rows	= [];
									$columns = 3;
									
									$answer = "Успішно";
									
									if($text < 1 || $text > 50){
										$answer = "Кількість вказано некоректно. Вкажіть кількість ⤵️";
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(3)
													->setActionType('reply')
													->setActionBody($tracking['back'])
													->setText('Назад');
									}else{
										unset($tracking['current-action']);
										
										$record = Payments::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'name'				=> $tracking['name'],
											'package_id'		=> $tracking['package_id'],
											'count_packages'	=> $text,
											'amount'			=> ($tracking['price'] * $text)
										]);
										
										$currentClass->notificationPay($record->id);
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 6)->first();
										
										if($data){
											$answer = $data->text;
											$answer = str_replace(':amount', ($tracking['price'] * $text), $answer);
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'app' && $tracking['type'] == 'construction'){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(3)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "тел./email ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-contact';
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('share-phone')
													->setActionBody('share-phone')
													->setText('Відправити номер телефону');
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setMinApiVersion(7)
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										$record = Applications::create([
											'client_id'			=> $client->id,
											'type'				=> $tracking['type'],
											'volume'			=> $tracking['volume'],
											'service'			=> $tracking['container_action'],
											'address'			=> $tracking['address'],
											'name'				=> $tracking['name'],
											'phone'				=> $phone
										]);
										
										$currentClass->notificationApplication($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 8)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											$record = Applications::create([
												'client_id'			=> $client->id,
												'type'				=> $tracking['type'],
												'volume'			=> $tracking['volume'],
												'service'			=> $tracking['container_action'],
												'address'			=> $tracking['address'],
												'name'				=> $tracking['name'],
												'email'				=> $text,
											]);
											
											$currentClass->notificationApplication($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 8)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
												
												$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
											}else{
												$record = Applications::create([
													'client_id'			=> $client->id,
													'type'				=> $tracking['type'],
													'volume'			=> $tracking['volume'],
													'service'			=> $tracking['container_action'],
													'address'			=> $tracking['address'],
													'name'				=> $tracking['name'],
													'phone'				=> $text,
												]);
												
												$currentClass->notificationApplication($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 8)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'application' && ($tracking['type'] == 'household' || $tracking['type'] == 'separate')){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									$columns = 6;
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "Додайте фото за наявності ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-photo';
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody($tracking['action'].'-'.$tracking['type'].'-finish')
													->setText('Завершити');
									}
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setMinApiVersion(7)
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'violation-schedule'){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу обслуговування ⤵️";
										
									}else{
										$answer = "ЄДРПОУ/назва ⤵️";
										
										$tracking['address']= $text;
										$tracking['back']	= $tracking['back']."-address";
										
										$tracking['current-action'] = 'get-name';
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 3 || $len > 100){
										$answer = "Дані вказано некоректно, вкажіть ЄДРПОУ або назву ⤵️";
									}else{
										$answer = "Додайте фото за наявності ⤵️";
										
										$tracking['name']	= $text;
										$tracking['back']	= $tracking['back']."-name";
										
										$tracking['current-action'] = 'get-photo';
										
										Actions::insert([
											'id'		=> md5($client->id.'-'.time()),
											'client_id'	=> $client->id,
											'tracking'	=> json_encode($tracking)
										]);
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('reply')
													->setActionBody($tracking['action'].'-'.$tracking['type'].'-finish')
													->setText('Завершити');
									}
									
									$columns = 6;
									
									if(false){
										$columns = 3;
										
										$rows[] = (new \Viber\Api\Keyboard\Button())
														->setColumns(3)
														->setActionType('reply')
														->setActionBody($tracking['back'])
														->setText('Назад');
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'review'){
								if($tracking['current-action'] == 'get-text'){
									$text = trim($text);
									//$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									
									$len = mb_strlen($text);
									
									if($len < 4){
										$answer = "Мінімум 4 символи ⤵️";
									}elseif($len > 1500){
										$answer = "Максимум 1500 символів ⤵️";
									}else{
										$answer = "Успішно";
										
										$record = Reviews::create([
											'client_id'	=> $client->id,
											'rating'	=> $tracking['rating'],
											'text'		=> $text
										]);
										
										$currentClass->notificationReview($record->id, $client);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 11)->first();
										
										if($data){
											$answer = $data->text;
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns(6)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'sale'){
								if($tracking['current-action'] == 'get-count'){
									$text = (int)trim($text);
									
									$rows = [];
									
									if($text < 1 || $text > 50){
										$answer = "Кількість вказано некоректно. Скільки штук? ⤵️";
									}else{
										$answer = "Покупець (ПІБ/ФОП/ТОВ) ⤵️";
										
										$tracking['count']			= $text;
										$tracking['current-action'] = "get-name";
									}
									
									$columns = 6;
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									
									$len = mb_strlen($text);
									
									if($len < 3){
										$answer = "Мінімум 3 символи ⤵️";
									}elseif($len > 150){
										$answer = "Максимум 150 символів ⤵️";
									}else{
										$tracking['name'] = $text;
										
										$tracking['current-action'] = 'get-contact';
										
										$answer = "тел./email ⤵️";
									}
									
									$rows = [];
									
									$columns = 6;
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										$record = SaleContainers::create([
											'client_id'			=> $client->id,
											'value'				=> $tracking['value'],
											'color'				=> $tracking['color'],
											'count'				=> $tracking['count'],
											'name'				=> $tracking['name'],
											'phone'				=> $phone
										]);
										
										$currentClass->notificationSale($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 12)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											$record = SaleContainers::create([
												'client_id'			=> $client->id,
												'value'				=> $tracking['value'],
												'color'				=> $tracking['color'],
												'count'				=> $tracking['count'],
												'name'				=> $tracking['name'],
												'email'				=> $text
											]);
											
											$currentClass->notificationSale($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 12)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
											}else{
												$record = SaleContainers::create([
													'client_id'			=> $client->id,
													'value'				=> $tracking['value'],
													'color'				=> $tracking['color'],
													'count'				=> $tracking['count'],
													'name'				=> $tracking['name'],
													'phone'				=> $text
												]);
												
												$currentClass->notificationSale($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 12)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
							
							if($tracking['action'] == 'purchase'){
								if($tracking['current-action'] == 'get-address'){
									if($location){
										if(isset($location['address']) && $location['address']){
											$text = $location['address'];
										}else{
											$text = $currentClass->getLocation($location['lat'], $location['lng']);
										}
									}else{
										$text = trim($text);
										$text = preg_replace('/[^а-яА-ЯіІёЁъЪєЄїЇ0-9\.\(\)\/\, ]/ui', '', $text);
									}
									
									$len = mb_strlen($text);
									
									$rows = [];
									
									if($len < 5 || $len > 150){
										$answer = "Адреса вказана некоректно, введіть адресу накопичення ⤵️";
										
									}else{
										$answer = "Яка орієнтовна вага, кг ⤵️";
										
										$tracking['address']= $text;
										
										$tracking['current-action'] = 'get-weight';
									}
									
									$columns = 6;
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-weight'){
									$text = preg_replace("/[^0-9\.,]/", '', $text);
									
									$rows = [];
									
									if($text < 1 || $text > 10){
										$answer = "Кількість вказано некоректно. Скільки штук? ⤵️";
									}else{
										$answer = "Покупець (ПІБ/ФОП/ТОВ) ⤵️";
										
										$tracking['weight']			= $text;
										$tracking['current-action'] = "get-name";
									}
									
									$columns = 6;
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-name'){
									$text = trim($text);
									
									$len = mb_strlen($text);
									
									if($len < 3){
										$answer = "Мінімум 3 символи ⤵️";
									}elseif($len > 150){
										$answer = "Максимум 150 символів ⤵️";
									}else{
										$tracking['name'] = $text;
										
										$tracking['current-action'] = 'get-contact';
										
										$answer = "тел./email ⤵️";
									}
									
									$rows = [];
									
									$columns = 6;
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
								
								if($tracking['current-action'] == 'get-contact'){
									$text = trim($text);
									
									$rows	= [];
									$answer	= "Успішно";
									$columns= 3;
									
									if($phone){
										$record = Purchase::create([
											'client_id'			=> $client->id,
											'name'				=> $tracking['name'],
											'weight'			=> $tracking['weight'],
											'address'			=> $tracking['address'],
											'type'				=> str_replace('_', '-', $tracking['type']),
											'phone'				=> $phone
										]);
										
										$currentClass->notificationPurchase($record->id);
										
										$tracking = [];
										
										$columns = 6;
										
										$data = Contents::query()->where('id', 13)->first();
										
										if($data){
											$answer = $data->text;
										}
									}else{
										if(filter_var($text, FILTER_VALIDATE_EMAIL)){
											$record = Purchase::create([
												'client_id'			=> $client->id,
												'name'				=> $tracking['name'],
												'weight'			=> $tracking['weight'],
												'address'			=> $tracking['address'],
												'type'				=> str_replace('_', '-', $tracking['type']),
												'email'				=> $text
											]);
											
											$currentClass->notificationPurchase($record->id);
											
											$tracking = [];
											
											$columns = 6;
											
											$data = Contents::query()->where('id', 13)->first();
											
											if($data){
												$answer = $data->text;
											}
										}else{
											$text	= preg_replace('/[^0-9]/ui', '', $text);
											
											$len	= mb_strlen($text);
											
											if($len < 9 || $len > 25){
												$answer	= "Некоректні контактні дані";
											}else{
												$record = Purchase::create([
													'client_id'			=> $client->id,
													'name'				=> $tracking['name'],
													'weight'			=> $tracking['weight'],
													'address'			=> $tracking['address'],
													'type'				=> str_replace('_', '-', $tracking['type']),
													'phone'				=> $text
												]);
												
												$currentClass->notificationPurchase($record->id);
												
												$tracking = [];
												
												$columns = 6;
												
												$data = Contents::query()->where('id', 13)->first();
												
												if($data){
													$answer = $data->text;
												}
											}
										}
									}
									
									$rows[] = (new \Viber\Api\Keyboard\Button())
													->setColumns($columns)
													->setActionType('reply')
													->setActionBody('home')
													->setText('До головної');
									
									$bot->getClient()->sendMessage(
											(new \Viber\Api\Message\Text())
											->setSender($botSender)
											->setReceiver($receiverId)
											->setText($answer)
											->setTrackingData(json_encode($tracking))
											->setKeyboard(
												(new \Viber\Api\Keyboard())->setButtons($rows)
											)
										);
									
									return false;
								}
							}
						}
					}
				}
			})
			->run();
		}catch(Exception $e){
			//$log->warning('Exception: ', $e->getMessage());
			
			file_put_contents(LOGS_PATH.'/bot.log', 'Exception:', FILE_APPEND);
			file_put_contents(LOGS_PATH.'/bot.log', "\n", FILE_APPEND);
			file_put_contents(LOGS_PATH.'/bot.log', print_r($e->getMessage(), true), FILE_APPEND);
			file_put_contents(LOGS_PATH.'/bot.log', "\n", FILE_APPEND);
			
			if($bot){
				//$log->warning('Actual sign: ' . $bot->getSignHeaderValue());
				//$log->warning('Actual body: ' . $bot->getInputBody());
			}
		}
	}
}
