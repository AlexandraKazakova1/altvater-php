<?php

namespace App\Helpers;

//use XMLWriter;

class SiteMapHelper {
	
    const SCHEMA			= 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const DEFAULT_FILENAME	= 'sitemap';
    
    private $baseUrl;
    private $xmlWriter;
    
    public function __construct(){
        $this->xmlWriter = new \XMLWriter();
        
        $this->baseUrl = public_path(self::DEFAULT_FILENAME . '.xml');
        
        $this->openXml();
    }
    
    public function __destruct(){
        $this->xmlWriter->endElement();
        $this->xmlWriter->endDocument();
    }
    
    protected function openXml(){
        $this->xmlWriter->openURI($this->baseUrl);
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startElement('urlset');
        $this->xmlWriter->writeAttribute('xmlns', self::SCHEMA);
    }
    
    public function addItem(string $loc, string $lastmod, string $changefreq = 'monthly', float $priority = 0.5){
        $this->xmlWriter->startElement('url');
        
        if(filter_var($loc, FILTER_VALIDATE_URL) === false){
            $this->xmlWriter->writeElement('loc', url($loc));
        } else {
            $this->xmlWriter->writeElement('loc', $loc);
        }
        
        if($lastmod){
            $this->xmlWriter->writeElement('lastmod', $lastmod);
        }
        
        $this->xmlWriter->writeElement('changefreq', $changefreq);
        $this->xmlWriter->writeElement('priority', $priority);
        
        $this->xmlWriter->endElement();
    }
}
