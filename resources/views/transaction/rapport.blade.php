@extends('layouts.app')

@section('contenu')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-6 text-gray-800">Rapport Mensuel</h1>

  @foreach($transactions_par_mois as $mois => $group)
    @php
      $mois_date = \Carbon\Carbon::createFromFormat('Y-m', $mois);
      $depot = $group->where('type', 'depot')->first()->total ?? 0;
      $retrait = $group->where('type', 'debit')->first()->total ?? 0;
    @endphp

    <div class="bg-white rounded shadow p-4 mb-6">
      <h2 class="text-lg font-semibold mb-4 text-blue-700">
        {{ $mois_date->translatedFormat('F Y') }}
      </h2>

      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 text-left text-gray-600">Type</th>
            <th class="px-4 py-2 text-left text-gray-600">Montant Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr>
            <td class="px-4 py-2">Dépôts</td>
            <td class="px-4 py-2 text-green-600 font-bold">+ FCFA {{ number_format($depot, 2, ',', ' ') }}</td>
          </tr>
          <tr>
            <td class="px-4 py-2">Retraits</td>
            <td class="px-4 py-2 text-red-600 font-bold">- FCFA {{ number_format($retrait, 2, ',', ' ') }}</td>
          </tr>
          
        </tbody>
      </table>
      <div class="bg-white rounded shadow p-4 mb-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold text-blue-700">
      {{ $mois_date->translatedFormat('F Y') }}
    </h2>
    <a href="{{ route('transactions.rapport.pdf', ['annee_mois' => $mois_date->format('Y-m')]) }}"
       class="text-blue-600 hover:underline text-sm">
      📄 Exporter le PDF
    </a>
  </div>
    </div>
  @endforeach
</div>
@endsection
