<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Transactions - {{ $annee }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .mois-section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-green {
            color: green;
        }
        .text-red {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Rapport Annuel des Transactions - {{ $annee }}</h1>

    @foreach($transactions_par_mois as $mois => $group)
        @php
            $mois_date = \Carbon\Carbon::createFromFormat('Y-m', $mois);
            $depot = $group->where('type', 'depot')->first()->total ?? 0;
            $retrait = $group->where('type', 'debit')->first()->total ?? 0;
        @endphp

        <div class="mois-section">
            <h2>{{ $mois_date->translatedFormat('F Y') }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dépôts</td>
                        <td class="text-green">+ FCFA {{ number_format($depot, 2, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td>Retraits</td>
                        <td class="text-red">- FCFA {{ number_format($retrait, 2, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <p style="margin-top: 50px; font-size: 10px; text-align: center;">
        Rapport généré le {{ \Carbon\Carbon::now()->translatedFormat('d F Y à H:i') }}
    </p>
</body>
</html>
