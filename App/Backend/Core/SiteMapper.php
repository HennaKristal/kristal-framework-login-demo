<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class SiteMapper
{
    private string $sitemapPath;
    private array $registeredRoutes;
    private string $lastRenderedURL;
    private string $lastRenderedTemplate;
    private int $sitemapLastModified;

    public function __construct(array $routes, string $lastRenderedURL, string $lastRenderedTemplate)
    {
        $this->registeredRoutes = $routes;
        $this->lastRenderedTemplate = $lastRenderedTemplate;
        $this->sitemapPath = PATH_ROOT . "sitemap.xml";

        // Synchronize sitemap if it already exists
        if (file_exists($this->sitemapPath))
        {
            $this->lastRenderedURL = $lastRenderedURL;
            $this->sitemapLastModified = filemtime($this->sitemapPath);
            $this->synchronizeSitemap();
            return;
        }

        // Create new sitemap if it doesn't exist
        $this->createSitemap($this->sitemapPath);
    }

    private function createSitemap(): void
    {
        $sitemap = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        
        foreach ($this->registeredRoutes as $route => $handler)
        {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', esc_url(URL_BASE . $route));
            $url->addChild('lastmod', date('c'));
        }
        
        // Create a DOMDocument and import the SimpleXMLElement
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $domElement = dom_import_simplexml($sitemap);
        $domElement = $dom->importNode($domElement, true);
        $domElement = $dom->appendChild($domElement);

        // Save the formatted XML
        $dom->save('sitemap.xml');
    }

    private function synchronizeSitemap(): void
    {
        $updateAvailable = false;
        $sitemap = simplexml_load_file($this->sitemapPath);

        // Build a map of existing <loc> entries
        $existingEntries = [];
        foreach ($sitemap->url as $entry)
        {
            $loc = (string)$entry->loc;
            $existingEntries[$loc] = $entry;
        }
    
        // Add missing routes
        foreach ($this->registeredRoutes as $route => $handler)
        {
            $loc = esc_url(URL_BASE . $route);
    
            if (!isset($existingEntries[$loc]))
            {
                $url = $sitemap->addChild("url");
                $url->addChild("loc", $loc);
                $url->addChild("lastmod", date("c"));
                $updateAvailable = true;
            }
        }
    
        // Delete old depricated entries
        foreach ($existingEntries as $loc => $entry)
        {
            $route = str_replace(URL_BASE, "", $loc);
    
            if (!isset($this->registeredRoutes[$route]))
            {
                $dom = dom_import_simplexml($entry);
                $dom->parentNode->removeChild($dom);
                $updateAvailable = true;
            }
        }
    
        // Update lastRenderedTemplate timestamp
        $templatePath = PATH_TEMPLATES . $this->lastRenderedTemplate;

        if (file_exists($templatePath))
        {
            $pageLastModified = filemtime($templatePath);
            $renderedRouteLoc = esc_url(URL_BASE . $this->lastRenderedURL);
        
            foreach ($sitemap->url as $entry)
            {
                if ((string)$entry->loc === $renderedRouteLoc)
                {
                    // Read lastmod from sitemap entry
                    $sitemapEntryLastMod = strtotime((string)$entry->lastmod);
    
                    // Compare template last modified time vs entry timestamp
                    if ($pageLastModified > $sitemapEntryLastMod)
                    {
                        $entry->lastmod = date("c", $pageLastModified);
                        $updateAvailable = true;
                    }

                    break;
                }
            }
        }

        // Save only if anything changed
        if ($updateAvailable)
        {
            $dom = new \DOMDocument("1.0");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($sitemap->asXML());
            $dom->save($this->sitemapPath);
        }
    }
}
