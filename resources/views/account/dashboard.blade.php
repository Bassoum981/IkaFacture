<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes factures — Ikafacture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
<header class="border-b border-slate-800 bg-slate-950/95">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
        <a href="{{ route('account.dashboard') }}" class="text-xl font-bold tracking-tight text-indigo-400">Ika<span class="text-white">facture</span></a>
        <div class="flex items-center gap-3">
            <span class="hidden text-sm text-slate-400 sm:inline">{{ auth()->user()->name }}</span>
            <a href="{{ route('facture.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500">Nouvelle facture</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg border border-slate-700 px-3 py-2 text-sm text-slate-300 transition hover:border-slate-500 hover:text-white">Déconnexion</button>
            </form>
        </div>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-8 md:px-8">
    <div class="mb-8">
        <p class="mb-2 text-sm font-medium text-indigo-400">Espace personnel</p>
        <h1 class="text-3xl font-semibold tracking-tight text-white">Mes factures</h1>
        <p class="mt-2 text-slate-400">Retrouvez ici les factures créées avec votre compte.</p>
    </div>

    @if($factures->isEmpty())
        <section class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/60 px-6 py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-300">+</div>
            <h2 class="mt-4 text-lg font-semibold text-white">Aucune facture enregistrée</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-400">Créez votre première facture pour commencer votre historique.</p>
            <a href="{{ route('facture.create') }}" class="mt-6 inline-flex rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-500">Créer une facture</a>
        </section>
    @else
        <section class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-800 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-white">Historique</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ $factures->total() }} facture(s)</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-sm">
                    <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Numéro</th>
                        <th class="px-5 py-3 font-medium">Client</th>
                        <th class="px-5 py-3 font-medium">Émission</th>
                        <th class="px-5 py-3 text-right font-medium">Total TTC</th>
                        <th class="px-5 py-3 text-right font-medium">Statut</th>
                        <th class="px-5 py-3 text-right font-medium">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                    @foreach($factures as $facture)
                        <tr class="transition hover:bg-slate-800/50">
                            <td class="px-5 py-4 font-medium text-indigo-300">{{ $facture->numero_facture }}</td>
                            <td class="px-5 py-4 text-slate-200">{{ $facture->client_nom }}</td>
                            <td class="px-5 py-4 text-slate-400">{{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-right font-medium text-white">{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</td>
                            <td class="px-5 py-4 text-right"><span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">{{ ucfirst($facture->statut) }}</span></td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('factures.download', $facture) }}" class="inline-flex rounded-lg border border-indigo-500/40 px-3 py-1.5 text-xs font-medium text-indigo-300 transition hover:border-indigo-400 hover:bg-indigo-500/10 hover:text-indigo-200">
                                    Régénérer le PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($factures->hasPages())
                <div class="border-t border-slate-800 px-5 py-4">{{ $factures->links() }}</div>
            @endif
        </section>
    @endif
</main>
@include('partials.legal-footer')
</body>
</html>
