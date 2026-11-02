<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Ikafacture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4 text-slate-100">
<main class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl md:p-8">
    <a href="{{ route('facture.create') }}" class="text-xl font-bold tracking-tight text-indigo-400">Ika<span class="text-white">facture</span></a>
    <h1 class="mt-8 text-2xl font-semibold text-white">Bienvenue</h1>
    <p class="mt-2 text-sm text-slate-400">Connectez-vous pour retrouver vos factures.</p>
    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2.5 text-white outline-none transition focus:border-indigo-500">
            @error('email')<p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-300">Mot de passe</label>
            <input id="password" name="password" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2.5 text-white outline-none transition focus:border-indigo-500">
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-400"><input type="checkbox" name="remember" value="1" class="rounded border-slate-700 bg-slate-950 text-indigo-600"> Se souvenir de moi</label>
        <button class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">Se connecter</button>
    </form>
    <p class="mt-6 text-center text-sm text-slate-500">Pas encore de compte ? Créez-en un après avoir généré votre première facture.</p>
</main>
@include('partials.legal-footer')
</body>
</html>
