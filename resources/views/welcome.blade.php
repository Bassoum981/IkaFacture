<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IkaFacture — La facturation professionnelle, sans friction</title>
    @include('partials.seo', [
        'title' => 'IkaFacture — Créez vos factures professionnelles simplement',
        'description' => 'IkaFacture est une application web simple pour créer, télécharger et retrouver vos factures professionnelles.',
    ])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
<header class="border-b border-slate-800">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-indigo-400">Ika<span class="text-white">facture</span></a>
        <nav class="flex items-center gap-3" aria-label="Navigation principale">
            <a href="{{ route('legal.notice') }}" class="hidden text-sm text-slate-400 transition hover:text-white sm:inline">Mentions légales</a>
            <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-3 py-2 text-sm text-slate-300 transition hover:border-slate-500 hover:text-white">Se connecter</a>
            <a href="{{ route('facture.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Créer une facture</a>
        </nav>
    </div>
</header>
<main>
    <section class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 md:grid-cols-[1.05fr_.95fr] md:px-8 md:py-28">
        <div>
            <p class="mb-5 text-sm font-medium uppercase tracking-[0.2em] text-indigo-300">Facturation claire et rapide</p>
            <h1 class="max-w-3xl text-4xl font-semibold leading-tight tracking-tight text-white md:text-6xl">Vos factures professionnelles, prêtes en quelques minutes.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-400">IkaFacture est une application web pensée pour les indépendants, petites entreprises et créateurs qui veulent produire des factures propres sans perdre du temps dans des outils compliqués.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('facture.create') }}" class="rounded-lg bg-indigo-600 px-5 py-3 font-medium text-white transition hover:bg-indigo-500">Commencer gratuitement</a>
                <a href="#fonctionnalites" class="rounded-lg border border-slate-700 px-5 py-3 font-medium text-slate-300 transition hover:border-slate-500 hover:text-white">Découvrir IkaFacture</a>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-indigo-950/30">
            <div class="flex items-center justify-between border-b border-slate-800 pb-5">
                <div><p class="text-xs uppercase tracking-wider text-slate-500">Aperçu</p><h2 class="mt-1 text-xl font-semibold text-white">FACTURE</h2></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white" aria-hidden="true">IF</div>
            </div>
            <div class="space-y-4 py-6"><div class="h-3 w-2/5 rounded bg-slate-700"></div><div class="h-3 w-4/5 rounded bg-slate-800"></div><div class="h-16 rounded-xl border border-slate-800 bg-slate-950"></div><div class="ml-auto h-3 w-1/3 rounded bg-indigo-500/60"></div></div>
            <div class="flex items-center justify-between border-t border-slate-800 pt-5 text-sm"><span class="text-slate-500">Total TTC</span><span class="font-semibold text-white">Document PDF prêt</span></div>
        </div>
    </section>
    <section id="fonctionnalites" class="border-y border-slate-800 bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 py-16 md:px-8">
            <h2 class="text-2xl font-semibold text-white">Tout ce qu’il faut pour facturer sereinement</h2>
            <div class="mt-8 grid gap-5 md:grid-cols-3">
                <article class="rounded-2xl border border-slate-800 bg-slate-900 p-6"><h3 class="text-lg font-semibold text-white">Création rapide</h3><p class="mt-3 text-sm leading-6 text-slate-400">Ajoutez votre client, vos prestations, la TVA, une remise et les conditions de règlement dans un éditeur simple.</p></article>
                <article class="rounded-2xl border border-slate-800 bg-slate-900 p-6"><h3 class="text-lg font-semibold text-white">PDF professionnel</h3><p class="mt-3 text-sm leading-6 text-slate-400">Téléchargez un document cohérent avec votre aperçu, incluant les totaux, échéances, notes et votre logo.</p></article>
                <article class="rounded-2xl border border-slate-800 bg-slate-900 p-6"><h3 class="text-lg font-semibold text-white">Historique sécurisé</h3><p class="mt-3 text-sm leading-6 text-slate-400">Créez un compte pour retrouver vos factures et régénérer leurs PDF quand vous en avez besoin.</p></article>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-4xl px-4 py-16 text-center md:px-8">
        <h2 class="text-2xl font-semibold text-white">Prêt à créer votre première facture ?</h2>
        <p class="mx-auto mt-3 max-w-2xl text-slate-400">Commencez sans compte. Vous pourrez ensuite créer un espace personnel pour conserver votre historique.</p>
        <a href="{{ route('facture.create') }}" class="mt-7 inline-flex rounded-lg bg-indigo-600 px-5 py-3 font-medium text-white transition hover:bg-indigo-500">Ouvrir l’éditeur</a>
    </section>
</main>
@include('partials.legal-footer')
</body>
</html>
