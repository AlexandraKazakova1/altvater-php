<?php
/**
* Static optimizer
* Author Mirasoft
* Created for the project Dispatcher
*/

Class Compression{
    /**
     * Is cache rebuils required
     *
     * @var boolean
     */
    private $_isCacheRebuildRequired = false;
    
    /**
     * Max modify time of target css files
     */
    private $_maxModifyTime;
    
    /**
     *
     * @var string
     */
    private $_FileName;
    
    private $_CacheData = array();
    
    private $_CacheDir = CACHE_PATH;
    
    private $_Dir;
    
    private $_Type;
    
    public $_min = true;
    
    private $_less = false;
    
    public function compress($file, $path, $type){
        $this->_Type = $type;
        
        if(!is_array($file)){
            $str = str_replace(" ", '', $file);
            $array = explode(',', $str);
        }else{
            $array = $file;
            $str = implode(',', $file);
        }
        
        $file = str_replace(array('.css','.js',','), array('_','_',''), $str);
        $file = md5($file).'.'.$type;
        
        $this->CacheFileName = CACHE_PATH."/".$file;
        
        $this->_Dir = ROOT.'/'.$path."/";
        
        $this->_loadFilesCache();
        
        if(!$this->_isCacheRebuildRequired){
            $this->_check();
        }
        
        //$this->_less = new lessc;
        
        if($this->_isCacheRebuildRequired){
            $this->_generateFile($array);
            $this->_generateFileName();
            $this->_generateCache();
            
            $v = max(0, filemtime($this->CacheFileName));
            $this->RealFileName = $this->_Dir.'v'.$v.'.'.$file;
            
            if(!is_writable($this->_Dir)){
                if(!file_exists($this->CacheFileName)){
                    return '';
                }
                
                $v = max(0, filemtime($this->CacheFileName));
                $this->RealFileName = $this->_Dir.'v'.$v.'.'.$file;
                
                return 'v'.$v.'.'.$file;
            }
            
            $this->_gzipFile();
        }else{
            $v = max(0, filemtime($this->CacheFileName));
            $this->RealFileName = $this->_Dir.'v'.$v.'.'.$file;
        }
        
        return 'v'.$v.'.'.$file;
    }
    
    /**
    * Load Css Optimizer Cache
    */
    private function _loadFilesCache(){
        $CacheFile = $this->CacheFileName;
        
        if(is_file($CacheFile)){
            $Records = preg_split('/\n/', file_get_contents($CacheFile));
            
            $this->_FileName = array_shift($Records);
            
            array_pop($Records);
            
            foreach($Records as $Record){
                $RecordArray = preg_split('/\:/',$Record);
                $this->_insertCacheDataRecord($RecordArray[0],$RecordArray[1]);
            }
        }else{
            $this->_isCacheRebuildRequired = true;
        }
    }
    
    /**
    * Build All-In-One Optimized File
    *
    */
    private function _generateFile($array){
        foreach ($array as $fileName){
            $this->_insertCacheDataRecord($this->_Dir.$fileName,filemtime($this->_Dir.$fileName));
        }
    }
    
    /**
    * Generate FileName
    *
    * @return string
    */
    private function _generateFileName(){
        $this->_isCacheRebuildRequired = false;
        
        if($this->_Type == 'css'){
            $this->_FileName = 'css_'.max(array_values($this->_CacheData)). '.css';
        }else{
            $this->_FileName = 'js_'.max(array_values($this->_CacheData)). '.js';
        }
    }
    
    /**
    * Generate Optimizer cache-file
    *
    */
    private function _generateCache(){
        $CacheFile = $this->CacheFileName;
        
        if(!$CacheFileHandle = fopen($CacheFile,'w')){
            chmod($CacheFile, 777);
            $CacheFileHandle = fopen($CacheFile,'w');
        }
        
        if(flock($CacheFileHandle, LOCK_EX)){ // Exclusive lock
            fwrite($CacheFileHandle,$this->_FileName."\n");
            
            foreach ($this->_CacheData as $fileName => $filemtime){
                fwrite($CacheFileHandle,$fileName . ':' . filemtime($fileName)."\n");
            }
            
            flock($CacheFileHandle, LOCK_UN); // Unlock
        }
    }
    
    /**
    * Insert Info into _CacheData array
    *
    * @param string $FileName
    * @param path   $lastModifyTime
    */
    private  function _insertCacheDataRecord($FileName,$lastModifyTime){
        if (!count($this->_CacheData)) {
            $this->_mainFileName = $FileName;
        }
        
        $this->_CacheData[$FileName] = $lastModifyTime;
    }
    
    /**
    * Check is Classes is now in Cache
    *
    */
    private function _check(){
        foreach ($this->_CacheData as $fileName => $lastModifyTime){
            $lastModifyTimeNew = filemtime($fileName);
            
            if ($lastModifyTimeNew > $lastModifyTime) {
                $this->_isCacheRebuildRequired = true;
                return ;
            }
        }
    }
    
    /**
    * Make Gzip File
    * with .gz extension
    *
    */
    private function _gzipFile(){
        $FileGzipPath = $this->RealFileName.'.gz';
        
        if(!$FilePathHandle = fopen($FileGzipPath,'wb')){
            chmod($FileGzipPath, 777);
            $FilePathHandle = fopen($FileGzipPath,'wb');
        }
        
        /*
        if(!$FilePathHandle = gzopen($FileGzipPath, 'w')){
            return '';
        }
        */
        
        //stripos($str, 'курс') !== false
        
        if(!flock($FilePathHandle, LOCK_UN)){
            unlink($FileGzipPath);
            return false;
        }
        
        $data = "";
        
        $i = 0;
        
        foreach($this->_CacheData as $fileName => $lastModifyTime){
            if(!file_exists($fileName)){
                continue;
            }
            
            if($i > 0){
                $data .= "\n";
            }
            
            if(stripos($fileName, '.min') !== false){
                $ext = substr(strrchr($fileName, '.'), 1);
                
                if($ext == 'less' && $this->_less){
                    $this->_less->checkedCompile($fileName, $fileName.".css");
                    
                    if(!file_exists($fileName.".css")){
                        continue;
                    }
                    
                    $fileName = $fileName.".css";
                }
                
                $text = file_get_contents($fileName);
                
                if($this->_min){
                    // if($this->_Type != 'css'){
                    //    $text = preg_replace("#//([^\n)]+)\n#",'',$text);
                    //   $text = preg_replace("#//([^\r)]+)\r#",'',$text);
                    if($this->_Type == 'css'){
                        //$text = preg_replace("#\n#",'',$text); //убираем переносы строк
                        
                        //$text = preg_replace('/(\/\*).*?(\*\/)/s', '', $text); // Удаляем комментарии
                        
                        //$text = preg_replace("#\n+\s+#","\n",$text);//Убираем все пробелы в начале строк.
                        //$text = preg_replace("#\r\n|\r#","\n", $text); //убираем переносы строк
                        //$text = preg_replace("#\n#",'',$text); //убираем переносы строк
                    }else{
                        //$text = preg_replace("#[^:]//([^\n)]+)\n#",'',$text);
                        //$text = preg_replace("#[^:]//([^\r)]+)\r#",'',$text);
                        
                        //$text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$text);
                        
                        //$text = preg_replace("#\n+\s+#","\n",$text);//Убираем все пробелы в начале строк.
                    }
                    
                    //$text = preg_replace('#\t#','',$text);      //Убираем tab-отступы.
                }
                
                $data .= $text;
                
                //gzwrite($FilePathHandle, $text);
            }else{
                $text = file_get_contents($fileName);
                //$text = preg_replace('#\t#','',$text);      //Убираем tab-отступы.
                
                if($this->_Type == 'css'){
                    if($this->_min){
                        //$text = preg_replace("#\n+\s+#","\n",$text);//Убираем все пробелы в начале строк.
                        //$text = preg_replace("#\r\n|\r#","\n", $text); //убираем переносы строк
                        //$text = preg_replace("#\n#",'',$text); //убираем переносы строк
                        //$text = preg_replace('/(\/\*).*?(\*\/)/s', '', $text); // Удаляем комментарии
                        
                        //$text = preg_replace("#(\s*):(\s*)#",':',$text); //Удаляем пробелы
                        //$text = preg_replace("#(\s*);(\s*)#",';',$text); //Удаляем пробелы
                        //$text = preg_replace("#(\s*){(\s*)#",'{',$text);
                        //$text = preg_replace("#(\s*)}(\s*)#",'}',$text);
                        //$text = preg_replace("#(\s*),(\s*)#",',',$text);
                    }
                    
                    $data .= $text;
                    
                    //gzwrite($FilePathHandle, $text);
                    unset($text);
                }else{
                    if($this->_min){
                        //$text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$text);
                        //$text = preg_replace("#[^:]//([^\r)]+)\r#",'',$text);
                        
                        // удаляем многострочные комментарии /* */
                        //$text = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',$text);
                        //$text = preg_replace('/(\/\*).*?(\*\/)/s', '', $text); // Удаляем комментарии
                        
                        // удаляем строки начинающиеся с //
                        //$text = preg_replace('#//.*#','',$text);
                        //$text = preg_replace("#//(.*)[^\n]#",'',$text);
                        
                        //$text = preg_replace("#\n+\s+#","\n",$text);//Убираем все пробелы в начале строк.
                        
                        //$text = preg_replace("#\r\n|\r#","\n", $text);
                        //$text = preg_replace("#\n#",'',$text);
                        
                        //$text = preg_replace("#\s*:\s*#",':',$text); //Удаляем пробелы
                        //$text = preg_replace("#\s*;\s*#",';',$text); //Удаляем пробелы
                        //$text = preg_replace("#\s+{\s+#",'{',$text);
                        //$text = preg_replace("#\s+}\s+#",'}',$text);
                        //$text = preg_replace("#\s*=\s*#",'=',$text);
                    }
                    
                    $data .= $text;
                    
                    //gzwrite($FilePathHandle, $text);
                    unset($text);
                }
            }
            
            $i++;
        }
        
        $encoded = gzencode($data);
        unset($data);
        
        if($encoded){
            if(-1 == fwrite($FilePathHandle, $encoded)){
                unlink($FileGzipPath);
                return false;
            }
        }
        
        //fclose($FilePathHandle);
        
        //gzclose($FilePathHandle);
        
        flock($FilePathHandle, LOCK_UN); // Unlock
    }      
}
?>
