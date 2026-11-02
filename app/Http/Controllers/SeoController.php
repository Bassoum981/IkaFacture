<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = app()->environment('production')
            ? "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $routes = [
            ['url' => route('home'), 'priority' => '1.0'],
            ['url' => route('facture.create'), 'priority' => '0.9'],
            ['url' => route('login'), 'priority' => '0.5'],
            ['url' => route('legal.notice'), 'priority' => '0.3'],
        ];

        $xml = view('seo.sitemap', ['routes' => $routes])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
