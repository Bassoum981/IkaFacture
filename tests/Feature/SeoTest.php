<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_public_pages_expose_seo_metadata_and_favicon(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<html lang="fr">', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('favicon.svg', false);
    }

    public function test_robots_and_sitemap_are_available(): void
    {
        $this->get(route('seo.robots'))
            ->assertOk()
            ->assertHeader('content-type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /');

        $this->get(route('seo.sitemap'))
            ->assertOk()
            ->assertHeader('content-type', 'application/xml; charset=UTF-8')
            ->assertSee(route('home'), false)
            ->assertSee(route('facture.create'), false)
            ->assertSee('<priority>1.0</priority>', false);
    }
}
