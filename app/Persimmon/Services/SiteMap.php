<?php
/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/25
 * Time: 17:15
 */


namespace Persimmon\Services;

use Models\Posts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SiteMap
{
    /**
     * Return the content of the Site Map
     */
    public function getSiteMap()
    {
        if (Cache::has('site-map')) {
            return Cache::get('site-map');
        }
        $siteMap = $this->buildSiteMap();
        Cache::add('site-map', $siteMap, 120);
        return $siteMap;
    }

    /**
     * Build the Site Map
     */
    protected function buildSiteMap()
    {
        $posts = $this->getPosts();

        $dates = array_values($posts);
        sort($dates);
        $lastmod = last($dates);
        $url = trim(url('/'));
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?' . '>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml[] = '  <url>';
        $xml[] = "    <loc>$url</loc>";
        $xml[] = "    <lastmod>".$lastmod['created_at']."</lastmod>";
        $xml[] = '    <changefreq>weekly</changefreq>';
        $xml[] = '    <priority>0.8</priority>';
        $xml[] = '  </url>';

        foreach ($posts as $key => $item) {
            $xml[] = '  <url>';
            $xml[] = "    <loc>".route('post',[$item['flag']])."</loc>";
            $xml[] = "    <lastmod>".$item['created_at']."</lastmod>";
            $xml[] = "  </url>";
        }

        $xml[] = '</urlset>';

        return join("\n", $xml);
    }

    /**
     * Return all the posts as $url => $date
     */
    protected function getPosts()
    {
        return Posts::orderBy('id', 'desc')->select('created_at', 'flag')->get()->toArray();
    }
}