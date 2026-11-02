@php
    $seoTitle = $title ?? 'IkaFacture — Créez vos factures simplement';
    $seoDescription = $description ?? 'IkaFacture vous aide à créer, télécharger et retrouver vos factures professionnelles en quelques minutes.';
    $seoUrl = $canonical ?? url()->current();
    $seoImage = $image ?? asset('favicon.svg');
@endphp
<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ app()->environment('production') ? 'index, follow' : 'noindex, nofollow' }}">
<link rel="canonical" href="{{ $seoUrl }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="IkaFacture">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">
<link rel="preconnect" href="https://cdn.tailwindcss.com">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
