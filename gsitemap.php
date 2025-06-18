<?php
// Sitemap extension, https://github.com/annaesvensson/yellow-sitemap

class YellowGsitemap {
    const VERSION = "0.3";
    public $yellow;         // access to API
    
    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
    }

    // Handle page layout
     public function onParsePageOutput($page, $text) {
        if ($page->get("location") == "/gsitemap.txt") {
            $output = "";
            foreach ($this->yellow->pages->index() as $p) {
                if (!$p->isAvailable() || $p->get("status") == "draft") continue;
                $output .= $p->location(true) . "\n";
            }
            $page->setLastModified($this->yellow->pages->getModified());
            $this->yellow->page->setHeader("Content-Type: text/plain; charset=utf-8");
            $page->setOutput($output);
            return false; // Prevents the rest of the page rendering
        }

        return $text; // Default behavior for all other pages
    }
    }
    
