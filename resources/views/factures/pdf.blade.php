<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.5; margin: 0; padding: 20px; }
        .header { width: 100%; margin-bottom: 40px; }
        .header table { width: 100%; border-collapse: collapse; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #1e293b; }
        .invoice-details { font-size: 12px; color: #64748b; margin-top: 5px; }
        .client-info { text-align: right; }
        .client-name { font-weight: bold; font-size: 16px; color: #1e293b; }
        .client-address { font-size: 12px; color: #64748b; white-space: pre-line; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 30px; margin-bottom: 40px; }
        table.items th { background-color: #f1f5f9; color: #475569; font-size: 11px; text-transform: uppercase; text-align: left; padding: 10px; border-bottom: 1px solid #cbd5e1; }
        table.items td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #334155; }
        table.items th.text-right, table.items td.text-right { text-align: right; }
        table.items th.text-center, table.items td.text-center { text-align: center; }

        .totals { width: 100%; margin-top: 20px; }
        .totals table { width: 250px; margin-left: auto; border-collapse: collapse; }
        .totals td { padding: 6px 10px; font-size: 13px; }
        .totals .label { color: #64748b; text-align: left; }
        .totals .value { font-weight: bold; text-align: right; color: #1e293b; }
        .totals .total-ttc { border-top: 2px solid #0f172a; font-size: 15px; color: #0f172a; }

        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; pt-10px; }
    </style>
</head>
<body>

<!-- En-tête -->
<div class="header">
    <table>
        <tr>
            <td>
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-details">N° {{ $facture->numero_facture }}</div>
                <div class="invoice-details">Date : {{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}</div>
            </td>
            <td class="client-info">
                <div class="client-name">{{ $facture->client_nom }}</div>
                <div class="client-address">{{ $facture->client_adresse }}</div>
            </td>
        </tr>
    </table>
</div>

<!-- Tableau des prestations -->
<table class="items">
    <thead>
    <tr>
        <th>Description</th>
        <th class="text-center">Qté</th>
        <th class="text-right">Prix Unitaire</th>
        <th class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($facture->lignes as $ligne)
        <tr>
            <td>{{ $ligne->description }}</td>
            <td class="text-center">{{ $ligne->quantite }}</td>
            <td class="text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            <td class="text-right" style="font-weight: 600;">{{ number_format($ligne->prix_total, 0, ',', ' ') }} FCFA</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Totaux -->
<div class="totals">
    <table>
        <tr>
            <td class="label">Total HT :</td>
            <td class="value">{{ number_format($facture->total_ht, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td class="label">TVA ({{ $facture->taux_tva }}%) :</td>
            <td class="value">{{ number_format($facture->montant_tva, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr class="total-ttc">
            <td class="label" style="color: #0f172a; font-weight: bold;">Total TTC :</td>
            <td class="value">{{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>
</div>

<!-- Pied de page -->
<div class="footer">
    Généré par Ikafacture — Solution de facturation simple et rapide
</div>

</body>
</html>
