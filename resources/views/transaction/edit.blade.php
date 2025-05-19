@extends('layouts.app')
@section('contenu')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg my-8 border border-gray-100">
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Modifier la transaction</h2>
        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">ID: {{ $transaction->id }}</span>
    </div>

    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Type de transaction -->
            <div class="transition duration-300 ease-in-out transform hover:-translate-y-1">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de transaction</label>
                <div class="relative">
                    <select id="type" name="type" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none" required>
                        <option value="Dépot" {{ $transaction->type == 'Dépot' ? 'selected' : '' }}>Dépôt</option>
                        <option value="Retrait" {{ $transaction->type == 'Retrait' ? 'selected' : '' }}>Retrait</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Montant -->
            <div class="transition duration-300 ease-in-out transform hover:-translate-y-1">
                <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant (F)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">F</span>
                    </div>
                    <input type="number" step="0.01" id="montant" name="montant" value="{{ $transaction->montant }}" 
                           class="w-full border border-gray-300 pl-10 pr-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Catégorie -->
            <div class="transition duration-300 ease-in-out transform hover:-translate-y-1">
                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <div class="relative">
                    <select id="categorie" name="categorie_id" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none" required>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ $categorie->id == $transaction->categorie_id ? 'selected' : '' }}>
                                {{ $categorie->libelle }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Date -->
            <div class="transition duration-300 ease-in-out transform hover:-translate-y-1">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input type="date" id="date" name="date" value="{{ $transaction->date }}" 
                           class="w-full border border-gray-300 pl-10 pr-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="transition duration-300 ease-in-out transform hover:-translate-y-1">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" rows="3" 
                      class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ $transaction->description }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Ajoutez des détails pour faciliter le suivi de cette transaction.</p>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <a href="{{ route('liste_transactions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Annuler
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    // Script pour mettre en évidence visuellement le type de transaction
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const montantInput = document.getElementById('montant');
        
        function updateTypeStyle() {
            if (typeSelect.value === 'Dépot') {
                typeSelect.classList.remove('bg-red-50', 'border-red-200');
                typeSelect.classList.add('bg-green-50', 'border-green-200');
            } else {
                typeSelect.classList.remove('bg-green-50', 'border-green-200');
                typeSelect.classList.add('bg-red-50', 'border-red-200');
            }
        }
        
        // Initialiser le style
        updateTypeStyle();
        
        // Mettre à jour le style lors du changement
        typeSelect.addEventListener('change', updateTypeStyle);
    });
</script>
@endsection