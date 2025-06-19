<?php

class YellowGsitemap {
    const VERSION = "0.6";
    public $yellow;         
    
    
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("Gsitemap","/gsitemap/"); //create a page or directory for this
        $this->yellow->system->setDefault("GsitemapTxt","/gsitemap.txt");
    }

     public function onParsePageOutput($page, $text) {
        if ($page->getLocation() == $this->yellow->system->get("Gsitemap")) {
            $output = "";
            $pages = $this->yellow->content->index(false, false);
            foreach ($pages as $p) {
                if (!$p->isAvailable() || $p->get("status") == "draft") continue;
                $output .= $p->getUrl() . "\n";
            }
            $page->setLastModified($pages->getModified());
            $this->yellow->page->setHeader("Content-Type:","text/plain; charset=utf-8");
            $text = $output;
        }

        return $text; // Default behavior for all other pages
    }
    
    public function onRequest($scheme, $host, $base, $location, $file) {
        if (preg_match("#^(.*)\.html$#", $location, $matches)) {
            $cleanLocation = $matches[1];
            if ($this->yellow->lookup->isFileLocation($cleanLocation)){
                $out = $this->yellow->lookup->normaliseUrl($scheme, $host, $base, $cleanLocation, $filterStrict = true);
            }
            $statusCode = $this->yellow->sendStatus(303, $out);
            return $statusCode;
        }
        
        if ($location == $this->yellow->system->get("GsitemapTxt")) {

            $pages = $this->yellow->content->index(false, false);
            $output = "";
            foreach ($pages as $p) {
                if (!$p->isAvailable() || $p->get("status") == "draft") continue;
                $output .= $p->getUrl() . "\n";
            }
            $this->yellow->page->set("layout", "none");
            $this->yellow->page->set("type", "text");
            $this->yellow->page->setLastModified($pages->getModified());
            $this->yellow->page->setHeader("Content-Type", "text/plain; charset=utf-8");
            $this->yellow->page->setOutput($output);
            header("Content-Type: text/plain; charset=utf-8");
            echo $output;
            return true;
        }

     return false;
    }



}
