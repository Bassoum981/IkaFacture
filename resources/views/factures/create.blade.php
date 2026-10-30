<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikafacture - Générateur de Facture Instantané</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen" x-data="ikaApp()">

<!-- En-tête -->
<header class="border-b border-slate-800 p-4 flex justify-between items-center max-w-7xl mx-auto">
    <h1 class="text-xl font-bold tracking-tight text-indigo-400">Ika<span class="text-white">facture</span></h1>

    <!-- Switcher Mobile (Stratégie 2 : Onglets Édition / Aperçu) -->
    <div class="flex md:hidden bg-slate-900 p-1 rounded-lg border border-slate-800">
        <button @click="activeTab = 'form'" :class="activeTab === 'form' ? 'bg-indigo-600 text-white' : 'text-slate-400'" class="px-3 py-1.5 text-xs font-medium rounded-md transition">Édition</button>
        <button @click="activeTab = 'preview'" :class="activeTab === 'preview' ? 'bg-indigo-600 text-white' : 'text-slate-400'" class="px-3 py-1.5 text-xs font-medium rounded-md transition">Aperçu A4</button>
    </div>
</header>

<!-- Conteneur Principal -->
<main class="max-w-7xl mx-auto p-4 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- COLONNE GAUCHE : FORMULAIRE D'ÉDITION -->
    <div :class="activeTab === 'form' ? 'block' : 'hidden md:block'" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-6">
        <h2 class="text-lg font-semibold text-white border-b border-slate-800 pb-3">Informations de la facture</h2>

        <!-- Logo de l'entreprise -->
        <div>
            <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Logo de l'entreprise</label>
            <input type="file" @change="handleLogoUpload" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
        </div>

        <!-- Nom du client -->
        <div>
            <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Nom du client / Entreprise</label>
            <input type="text" x-model="client.nom" placeholder="Ex: Kouamé SARL" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Adresse du client</label>
            <textarea x-model="client.adresse" placeholder="Quartier, Ville..." rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"></textarea>
        </div>

        <!-- Paramètre TVA et Remise globale -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Taux de TVA (%)</label>
                <input type="number" x-model.number="tauxTva" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Remise globale (FCFA)</label>
                <input type="number" min="0" x-model.number="remise" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <!-- Date d'échéance et Conditions de règlement -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Date d'échéance</label>
                <input type="date" x-model="dateEcheance" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Conditions de règlement</label>
                <select x-model="conditionsReglement" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
                    <option value="Paiement comptant">Paiement comptant</option>
                    <option value="30 jours">30 jours</option>
                    <option value="50% à la commande, 50% à la livraison">50% / 50%</option>
                </select>
            </div>
        </div>

        <!-- Notes / Instructions -->
        <div>
            <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Notes / Instructions / RIB</label>
            <textarea x-model="notes" placeholder="Ex: Merci pour votre confiance. Règlement par virement..." rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"></textarea>
        </div>

        <!-- SECTION : LIGNES DE PRESTATIONS -->
        <div class="border-t border-slate-800 pt-4 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Lignes de prestations</h3>
                <button @click="addItem()" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition">+ Ajouter une ligne</button>
            </div>

            <template x-for="(item, index) in items" :key="index">
                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl space-y-3 relative">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500 font-mono" x-text="`Prestation #${index + 1}`"></span>
                        <button @click="removeItem(index)" class="text-red-400 hover:text-red-300 text-xs font-medium" x-show="items.length > 1">Supprimer</button>
                    </div>
                    <div>
                        <input type="text" x-model="item.description" placeholder="Description de la prestation..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase">Quantité</label>
                            <input type="number" min="1" x-model.number="item.quantite" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-400 uppercase">Prix unitaire</label>
                            <input type="number" min="0" x-model.number="item.prix_unitaire" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- BOUTON DE SOUMISSION / TÉLÉCHARGEMENT PDF -->
        <div class="pt-4 border-t border-slate-800">
            <button @click="generatePDF()" :disabled="loading" class="w-full bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-medium py-3 rounded-xl transition shadow-lg shadow-indigo-600/20 flex justify-center items-center gap-2 cursor-pointer">
                <span x-text="loading ? 'Génération en cours...' : 'Générer et Télécharger le PDF'"></span>
            </button>
        </div>
    </div>

    <!-- COLONNE DROITE : APERÇU A4 EN DIRECT -->
    <div :class="activeTab === 'preview' ? 'block' : 'hidden md:block'" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl flex justify-center items-start overflow-auto">
        <!-- Feuille A4 Virtuelle -->
        <div class="bg-white text-slate-900 w-full max-w-[210mm] min-h-[297mm] p-8 shadow-2xl rounded-sm text-sm flex flex-col justify-between">
            <div>
                <!-- En-tête de la facture papier avec Logo -->
                <div class="flex justify-between items-start border-b pb-6 mb-6">
                    <div>
                        <!-- Affichage dynamique du logo -->
                        <template x-if="logoUrl">
                            <img :src="logoUrl" alt="Logo" class="h-16 object-contain mb-2">
                        </template>
                        <template x-if="!logoUrl">
                            <h3 class="text-2xl font-bold text-slate-800">FACTURE</h3>
                        </template>
                        <p class="text-slate-500 text-xs mt-1">N° FAC-2026-0001</p>
                        <p class="text-slate-500 text-xs" x-show="dateEcheance" x-text="`Échéance : ${dateEcheance}`"></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-800" x-text="client.nom || 'Nom du client'"></p>
                        <p class="text-slate-500 text-xs whitespace-pre-line" x-text="client.adresse || 'Adresse du client'"></p>
                    </div>
                </div>

                <!-- Tableau des prestations -->
                <table class="w-full text-left border-collapse mb-8">
                    <thead>
                    <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase">
                        <th class="py-2">Description</th>
                        <th class="py-2 text-center">Qté</th>
                        <th class="py-2 text-right">P.U.</th>
                        <th class="py-2 text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <template x-for="item in items">
                        <tr>
                            <td class="py-3 text-slate-700" x-text="item.description || 'Prestation...'"></td>
                            <td class="py-3 text-center text-slate-700" x-text="item.quantite"></td>
                            <td class="py-3 text-right text-slate-700" x-text="formatMoney(item.prix_unitaire)"></td>
                            <td class="py-3 text-right font-medium text-slate-900" x-text="formatMoney(item.quantite * item.prix_unitaire)"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <!-- Totaux, Remise et bas de page -->
            <div>
                <div class="flex justify-end mb-6">
                    <div class="w-64 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Total HT :</span>
                            <span class="font-semibold" x-text="formatMoney(calculateSubtotal())"></span>
                        </div>
                        <div class="flex justify-between text-slate-600" x-show="remise > 0">
                            <span>Remise :</span>
                            <span class="font-semibold text-red-600" x-text="`- ${formatMoney(remise)}`"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span x-text="`TVA (${tauxTva}%) :`"></span>
                            <span class="font-semibold" x-text="formatMoney(calculateTax())"></span>
                        </div>
                        <div class="flex justify-between text-slate-900 text-sm font-bold border-t pt-2">
                            <span>Total TTC :</span>
                            <span x-text="formatMoney(calculateTotalTTC())"></span>
                        </div>
                    </div>
                </div>

                <!-- Conditions et Notes affichées en bas -->
                <div class="border-t pt-4 text-xs text-slate-600 space-y-1 mb-6">
                    <p><strong class="text-slate-800">Conditions de règlement :</strong> <span x-text="conditionsReglement"></span></p>
                    <p x-show="notes"><strong class="text-slate-800">Notes :</strong> <span x-text="notes"></span></p>
                </div>

                <div class="text-xs text-slate-400 text-center border-t pt-4">
                    Généré par Ikafacture - Solution simple et rapide
                </div>
            </div>
        </div>
    </div>

</main>

<!-- Script Alpine.js avec gestion de la modale et de l'inscription -->
<script>
    function ikaApp() {
        return {
            activeTab: 'form',
            loading: false,
            showRegisterModal: false,
            tauxTva: 18,
            remise: 0,
            dateEcheance: '',
            conditionsReglement: 'Paiement comptant',
            notes: '',
            logoUrl: null,
            logoFile: null,
            client: {
                nom: '',
                adresse: ''
            },
            items: [
                { description: 'Développement application web', quantite: 1, prix_unitaire: 250000 }
            ],
            registerForm: {
                name: '',
                email: '',
                password: ''
            },

            handleLogoUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.logoFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            addItem() {
                this.items.push({ description: '', quantite: 1, prix_unitaire: 0 });
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            calculateSubtotal() {
                return this.items.reduce((sum, item) => sum + (item.quantite * item.prix_unitaire), 0);
            },

            calculateBaseRemise() {
                return Math.max(0, this.calculateSubtotal() - this.remise);
            },

            calculateTax() {
                return (this.calculateBaseRemise() * this.tauxTva) / 100;
            },

            calculateTotalTTC() {
                return this.calculateBaseRemise() + this.calculateTax();
            },

            formatMoney(amount) {
                return new Intl.NumberFormat('fr-FR').format(amount || 0) + ' FCFA';
            },

            generatePDF() {
                if (!this.client.nom) {
                    alert('Veuillez renseigner le nom du client.');
                    this.activeTab = 'form';
                    return;
                }

                this.loading = true;

                const formData = new FormData();
                formData.append('client', JSON.stringify(this.client));
                formData.append('tauxTva', this.tauxTva);
                formData.append('remise', this.remise);
                formData.append('date_echeance', this.dateEcheance);
                formData.append('conditions_reglement', this.conditionsReglement);
                formData.append('notes', this.notes);
                formData.append('items', JSON.stringify(this.items));

                if (this.logoFile) {
                    formData.append('logo', this.logoFile);
                }

                fetch('{{ route("factures.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(async response => {
                        if (!response.ok) {
                            const htmlError = await response.text();
                            const win = window.open();
                            win.document.write(htmlError);
                            throw new Error('Erreur serveur Laravel');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        const pdfBlob = new Blob([blob], { type: 'application/pdf' });
                        const url = window.URL.createObjectURL(pdfBlob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `facture-${Date.now()}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        this.loading = false;

                        // Ouvre automatiquement la modale après téléchargement réussi
                        this.showRegisterModal = true;
                    })
                    .catch(error => {
                        console.error(error);
                        this.loading = false;
                    });
            },

            registerUser() {
                fetch('{{ route("factures.register") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.registerForm)
                })
                    .then(async response => {
                        if (!response.ok) {
                            const err = await response.json();
                            alert('Erreur lors de l\'inscription : ' + (err.message || 'Vérifiez les champs'));
                            throw new Error('Erreur inscription');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => console.error(error));
            }
        }
    }
</script>

<!-- MODALE D'INVITATION À LA CRÉATION DE COMPTE -->
<div x-show="showRegisterModal"
     class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm flex justify-center items-center p-4 z-50"
     x-cloak>
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-2xl max-w-md w-full space-y-5">
        <div class="text-center space-y-2">
            <h3 class="text-lg font-bold text-white">Conservez l'historique de vos factures !</h3>
            <p class="text-xs text-slate-400">
                Votre facture a été téléchargée avec succès. Créez un compte gratuit en un clic pour la retrouver, ainsi que vos futurs documents, dans votre espace personnel.
            </p>
        </div>

        <!-- Formulaire d'inscription rapide -->
        <form @submit.prevent="registerUser" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Nom complet</label>
                <input type="text" x-model="registerForm.name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Adresse email</label>
                <input type="email" x-model="registerForm.email" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 uppercase mb-1">Mot de passe</label>
                <input type="password" x-model="registerForm.password" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="showRegisterModal = false" class="w-1/2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium py-2.5 rounded-xl transition cursor-pointer">
                    Passer pour l'instant
                </button>
                <button type="submit" class="w-1/2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/20 cursor-pointer">
                    Créer mon compte
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
