<?php

namespace App\Helpers;

use App\Helpers\Helper;

use DB;

class UseHelper extends Helper {

    public $_compress		= false;

    protected $_template	= 0;
    protected $_env			= '';
    protected $_minimize	= false;

    function __construct(){
		require_once(ROOT.'/../libs/Compression.php');

        $this->_env			= env('APP_ENV');
        $this->_minimize	= env('APP_MINIMIZE', false);
    }

    function set_template($id = 0){
        $this->_template = $id;

        return $this;
    }

    function stylesheet($file, $path="css", $type="all", $minimize = false){
        $type = trim($type);
        $path = trim($path);
        $path = trim($path, '/');

        if(($this->_env == 'local' || $this->_env == 'development') || $path == null || !$minimize){
            $min = false;
        } else {
            $min = true;
        }

        $compress = new \Compression;

        if(!$compress){
			$min = false;
		}

        if($this->_minimize === true && $min){
            //$compess->_min = false;

            $file = $compress->compress($file, $path, 'css');
            unset($compress);

            return "<link rel=\"stylesheet\" type=\"text/css\" media=\"".$type."\" href=\"/".$path."/".$file."\" />\n";
        }else{
			unset($compress);

            if(!is_array($file)){
                $file = explode(',',$file);
            }

            $out = "";

            foreach($file as $item){
                $item = trim($item);

                if($item != null){
                    $out .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"".$type."\" href=\"/".$path."/".$item."\" />\n";
                }
            }

            return $out;
        }
    }

    function javascript($file, $path="js", $min = false, $async = false, $defer = false){
        $path = trim($path, "/");

        if(($this->_env == 'local' || $this->_env == 'development') || $path == null){
            $min = false;
        }

        $compress = new \Compression;

        if(!$compress){
			$min = false;
		}

        //$min = false;

        if($this->_minimize === true && $min){
            //$compess->_min = false;

            $file = $compress->compress($file, $path, 'js');
            unset($compress);

            return "<script ".(($async) ? ' async ' : (($defer) ? ' defer ' : ''))." src=\"/".$path."/".$file."\" ></script>\n";
        }else{
			unset($compress);

            if(!is_array($file)){
                $file = explode(',',$file);
            }

            $out = "";

            foreach($file as $item){
                $item = trim($item);

                if($item != null){
                    if($path != null){
                        $out .= "<script ".(($async) ? ' async ' : (($defer) ? ' defer ' : ''))." src=\"/".$path."/".$item."\" ></script>\n";
                    }else{
                        $out .= "<script ".(($async) ? ' async ' : (($defer) ? ' defer ' : ''))." src=\"".$item."\" ></script>\n";
                    }
                }
            }

            return $out;
        }
    }
}
