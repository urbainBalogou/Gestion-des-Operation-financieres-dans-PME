@extends('layouts.app')
@section('contenu')
<!-- Script pour les animations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour les messages d'alerte
        const alertMessages = document.querySelectorAll('.alert-message');
        alertMessages.forEach(message => {
            // Afficher avec animation
            message.classList.add('animate-fade-in');
            
            // Disparaître après 5 secondes
            setTimeout(() => {
                message.classList.add('animate-fade-out');
                setTimeout(() => {
                    message.style.display = 'none';
                }, 500);
            }, 5000);
        });
        
        // Animation pour les éléments de la liste
        const listItems = document.querySelectorAll('.category-item');
        listItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 100}ms`;
            item.classList.add('animate-slide-in');
        });
    });
</script>

<!-- Styles spécifiques -->
<style>
    /* Animations */
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out forwards;
    }
    
    .animate-fade-out {
        animation: fadeOut 0.5s ease-in-out forwards;
    }
    
    .animate-slide-in {
        opacity: 0;
        animation: slideIn 0.3s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Titre de la page -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">Gestion des catégories</h2>
    
    <!-- Message de succès -->
    @if (session('success'))
        <div class="alert-message bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    <!-- Formulaire d'ajout -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Ajouter une nouvelle catégorie</h3>
        
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Libellé de la catégorie</label>
                <input 
                    type="text" 
                    name="libelle" 
                    id="libelle" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                    required
                    placeholder="Entrez le nom de la catégorie"
                >
            </div>
            <div>
                <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Liste des catégories -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            Liste des catégories
        </h3>
        
        @if(count($categories) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach ($categories as $category)
                    <li class="category-item py-3 flex items-center justify-between">
                        <span class="text-gray-800">{{ $category->libelle }}</span>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-flex">
                            @csrf 
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="py-4 text-center text-gray-500 italic">
                Aucune catégorie disponible
            </div>
        @endif
    </div>
</div>
@endsection