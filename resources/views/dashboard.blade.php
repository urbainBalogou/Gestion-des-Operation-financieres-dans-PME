@extends('layouts.app')
@section('contenu')
    
      <!-- Titre de la page -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500">Aperçu des finances de votre entreprise</p>
      </div>
      <!-- Filtres rapides
      <div class="flex flex-wrap items-center gap-3 mb-6">
        <div class="flex bg-white rounded-full p-1 shadow-sm border">
          <button
            class="px-4 py-1 rounded-full bg-primary text-white whitespace-nowrap !rounded-button"
          >
            Aujourd'hui
          </button>
          <button
            class="px-4 py-1 rounded-full text-gray-600 whitespace-nowrap !rounded-button"
          >
            Cette semaine
          </button>
          <button
            class="px-4 py-1 rounded-full text-gray-600 whitespace-nowrap !rounded-button"
          >
            Ce mois
          </button>
          <button
            class="px-4 py-1 rounded-full text-gray-600 whitespace-nowrap !rounded-button"
          >
            Cette année
          </button>
        </div>
        <div class="relative ml-auto">
          <button
            id="dateRangeBtn"
            class="flex items-center bg-white border rounded-full px-4 py-2 text-sm text-gray-600 whitespace-nowrap !rounded-button"
          >
            <div class="w-4 h-4 flex items-center justify-center mr-2">
              <i class="ri-calendar-line"></i>
            </div>
            16 Mai - 16 Juin 2025
            <div class="w-4 h-4 flex items-center justify-center ml-2">
              <i class="ri-arrow-down-s-line"></i>
            </div>
          </button>
        </div>
      </div> -->
      <!-- Cartes de synthèse -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      
        <div class="bg-white rounded shadow-sm p-5 border border-gray-100">
          <div class="flex justify-between mb-4">
            <div class="text-gray-500 text-sm">Dépôts</div>
            <div
              class="w-8 h-8 flex items-center justify-center bg-green-100 rounded-full text-green-600"
            >
              <i class="ri-arrow-down-circle-line"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-800">+ FCFA {{ number_format($totalDepot, 2, ',', ' ') }}</div>
          
        </div>
        <div class="bg-white rounded shadow-sm p-5 border border-gray-100">
          <div class="flex justify-between mb-4">
            <div class="text-gray-500 text-sm">Retraits</div>
            <div
              class="w-8 h-8 flex items-center justify-center bg-red-100 rounded-full text-red-600"
            >
              <i class="ri-arrow-up-circle-line"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-800">- FCFA {{ number_format($totalRetrait, 2, ',', ' ') }}</div>
          <div class="flex items-center mt-2 text-sm">
            <div
              class="w-4 h-4 flex items-center justify-center text-red-500 mr-1"
            >
              <i class="ri-arrow-down-line"></i>
            </div>
            <span class="text-red-500 font-medium">-3.2%</span>
            <span class="text-gray-500 ml-1">depuis le mois dernier</span>
          </div>
        </div>
        
      </div>
      <!-- Graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Évolution du solde -->
        <div
          class="bg-white rounded shadow-sm p-5 border border-gray-100 lg:col-span-2"
        >
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-800">Évolution du solde</h2>
            <div class="relative">
              <button
                id="balanceChartOptionsBtn"
                class="text-gray-400 hover:text-gray-600"
              >
                <div class="w-6 h-6 flex items-center justify-center">
                  <i class="ri-more-2-fill"></i>
                </div>
              </button>
            </div>
          </div>
          <div id="balanceChart" class="w-full h-72"></div>
        </div>
        <!-- Répartition des dépenses -->
        <div class="bg-white rounded shadow-sm p-5 border border-gray-100">
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-800">Répartition des dépenses</h2>
            <div class="relative">
              <button
                id="expensesChartOptionsBtn"
                class="text-gray-400 hover:text-gray-600"
              >
                <div class="w-6 h-6 flex items-center justify-center">
                  <i class="ri-more-2-fill"></i>
                </div>
              </button>
            </div>
          </div>
          <div id="expensesChart" class="w-full h-72"></div>
        </div>
      </div>
      <!-- Comparaison dépôts vs retraits -->
      <div class="bg-white rounded shadow-sm p-5 border border-gray-100 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="font-bold text-gray-800">
            Comparaison dépôts vs retraits
          </h2>
          <div class="relative">
            <button
              id="comparisonChartOptionsBtn"
              class="text-gray-400 hover:text-gray-600"
            >
              <div class="w-6 h-6 flex items-center justify-center">
                <i class="ri-more-2-fill"></i>
              </div>
            </button>
          </div>
        </div>
        <div id="comparisonChart" class="w-full h-72"></div>
      </div>
      <!-- Transactions récentes -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-gray-800 text-xl">Les transactions récentes</h2>
        <a
          href="{{ route('liste_transactions') }}"
          class="text-primary hover:underline text-sm flex items-center"
        >
          Voir toutes
          <div class="w-4 h-4 flex items-center justify-center ml-1">
            <i class="ri-arrow-right-line"></i>
          </div>
        </a>
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
</div>

    @endsection
    