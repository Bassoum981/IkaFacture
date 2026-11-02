<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales — Ikafacture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
<header class="border-b border-slate-800">
    <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 md:px-8">
        <a href="{{ route('facture.create') }}" class="text-xl font-bold tracking-tight text-indigo-400">Ika<span class="text-white">facture</span></a>
        <a href="{{ route('facture.create') }}" class="text-sm text-slate-400 transition hover:text-white">Retour à l’application</a>
    </div>
</header>

<main class="mx-auto max-w-3xl px-4 py-10 md:px-8">
    <h1 class="text-3xl font-semibold tracking-tight text-white">Mentions légales</h1>
    <p class="mt-3 text-sm text-slate-400">Informations relatives à l’éditeur et à l’utilisation d’IkaFacture.</p>

    <div class="mt-8 space-y-8 rounded-2xl border border-slate-800 bg-slate-900 p-6 text-sm leading-7 text-slate-300 md:p-8">
        <section>
            <h2 class="text-lg font-semibold text-white">Éditeur</h2>
            <p class="mt-2">IkaFacture est créé et maintenu par Ibrahim Bassoum.</p>
            <p><a href="https://ibassoum.dev" target="_blank" rel="noopener noreferrer" class="text-indigo-300 hover:text-indigo-200">ibassoum.dev</a></p>
        </section>
        <section>
            <h2 class="text-lg font-semibold text-white">Objet du service</h2>
            <p class="mt-2">IkaFacture permet de préparer des factures et de générer des documents PDF à partir des informations saisies par l’utilisateur.</p>
        </section>
        <section>
            <h2 class="text-lg font-semibold text-white">Données saisies</h2>
            <p class="mt-2">Les informations de compte et les factures enregistrées sont utilisées uniquement pour fournir l’historique et les fonctionnalités de facturation. L’utilisateur reste responsable de l’exactitude des informations saisies et de la conformité de ses factures avec la réglementation applicable.</p>
        </section>
        <section>
            <h2 class="text-lg font-semibold text-white">Responsabilité</h2>
            <p class="mt-2">Les modèles générés par IkaFacture ne constituent pas un conseil juridique, fiscal ou comptable. Il appartient à chaque utilisateur de vérifier les mentions obligatoires applicables à son activité et à son pays.</p>
        </section>
    </div>
</main>

@include('partials.legal-footer')
</body>
</html>
