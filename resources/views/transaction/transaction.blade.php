@extends('layouts.app')
@section('contenu')

 <h2 class="font-bold text-gray-900 text-xl">Liste des transactions</h2><br>
<form method="GET" action="{{ route('transactions.filter') }}" class="mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <input type="text" name="libelle" placeholder="Description" class="border px-3 py-2 rounded" value="{{ request('libelle') }}">
        <input type="number" name="montant" placeholder="Montant exact" class="border px-3 py-2 rounded" value="{{ request('montant') }}">
        <select name="type" class="border px-3 py-2 rounded">
            <option value="">Type</option>
            <option value="Dépot" {{ request('type') == 'Dépot' ? 'selected' : '' }}>Dépot</option>
            <option value="Retrait" {{ request('type') == 'Retrait' ? 'selected' : '' }}>Retrait</option>
        </select>
        <select name="categorie_id" class="border px-3 py-2 rounded">
            <option value="">Catégorie</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->libelle }}
                </option>
            @endforeach
        </select>
        <input type="month" name="mois" class="border px-3 py-2 rounded" value="{{ request('mois') }}" placeholder="mois">
        <button type="submit" class="col-span-2 md:col-span-1 bg-blue-600 text-white px-4 py-2 rounded">Rechercher</button>
    </div>
</form>
<div
      id="transactionModal"
      class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden"
    >
  <h2 class="font-bold text-gray-800 text-xl">Les transactions</h2>
        <!-- <a
          href="#"
          class="text-primary hover:underline text-sm flex items-center"
        >
          Voir toutes
          <div class="w-4 h-4 flex items-center justify-center ml-1">
            <i class="ri-arrow-right-line"></i>
          </div>
        </a> -->
      </div>
    
     <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden mb-6">
  <div class="overflow-x-auto transaction-table">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($transactions as $transaction)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('d F Y') }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="w-6 h-6 flex items-center justify-center rounded-full 
                  {{ $transaction->type === 'depot' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} mr-2">
                  <i class="{{ $transaction->type === 'depot' ? 'ri-arrow-down-line' : 'ri-arrow-up-line' }}"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">
                  {{ ucfirst($transaction->type) }}
                </span>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
              {{ $transaction->type === 'depot' ? 'text-green-600' : 'text-red-600' }}">
              {{ $transaction->type === 'depot' ? '+' : '-' }} FCFA {{ number_format($transaction->montant, 2, ',', ' ') }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                {{ $transaction->categorie->libelle ?? 'N/A' }}
              </span>
            </td>

            <td class="px-6 py-4 text-sm text-gray-500">
              {{ $transaction->description }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                {{ $transaction->piece_jointe ? 'Complété' : 'En attente' }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <!-- <a href="" class="text-gray-400 hover:text-primary">
                  <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-eye-line"></i>
                  </div>
                </a> -->
                <a href="{{ route('transactions.edit', $transaction->id) }}" class="text-gray-400 hover:text-primary">
                  <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-edit-line"></i>
                  </div>
                </a>
                <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}">
                  @csrf
                  @method('DELETE')
                  <button onclick="return confirm('Confirmer la suppression ?')" class="text-gray-400 hover:text-red-500">
                    <div class="w-6 h-6 flex items-center justify-center">
                      <i class="ri-delete-bin-line"></i>
                    </div>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection