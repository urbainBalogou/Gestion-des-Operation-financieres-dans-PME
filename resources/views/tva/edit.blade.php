@extends('layouts.app')

@section('contenu')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors">
                    <i class="ri-home-line"></i>
                    Tableau de bord
                </a>
            
            
                <i class="ri-arrow-right-s-line text-gray-400"></i>
                <span class="text-gray-900 font-medium">Configuration TVA</span>
            </nav>
            
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Configuration de la TVA</h1>
                    <p class="mt-2 text-gray-600">Gérez le taux de TVA appliqué aux transactions</p>
                </div>
                <div class="hidden sm:flex items-center space-x-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                    <i class="ri-information-line"></i>
                    <span class="text-sm font-medium">Paramètre global</span>
                </div>
            </div>
        </div>

        <!-- Alertes -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="ri-check-circle-line text-green-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="text-green-800 font-medium">Succès</h4>
                    <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="ri-error-warning-line text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-red-800 font-medium">Erreur</h4>
                    <ul class="text-red-700 text-sm mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ri-percent-line text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Modification du taux</h3>
                                <p class="text-sm text-gray-500">Définissez le pourcentage de TVA à appliquer</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.tva.update') }}" class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Taux actuel -->
                        @if(isset($tva) && $tva->taux)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="ri-information-line text-blue-600"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Taux actuel</p>
                                        <p class="text-2xl font-bold text-blue-900">{{ $tva->taux }}%</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Champ de saisie -->
                        <div class="space-y-2">
                            <label for="taux" class="block text-sm font-medium text-gray-700">
                                Nouveau taux de TVA (%)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    max="100" 
                                    name="taux" 
                                    id="taux"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('taux') border-red-300 @enderror" 
                                    value="{{ old('taux', $tva->taux ?? '') }}" 
                                    placeholder="Ex: 18.00"
                                    required
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            @error('taux')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">
                                Saisissez un taux entre 0 et 100%. Exemple: 18.00 pour 18%
                            </p>
                        </div>

                        <!-- Aperçu du calcul -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Aperçu du calcul</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Montant HT:</span>
                                        <span class="font-medium">1000 FCFA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">TVA (<span id="preview-rate">{{ $tva->taux ?? 0 }}</span>%):</span>
                                        <span class="font-medium text-blue-600" id="preview-tva">{{ isset($tva) ? number_format($tva->taux * 10, 0) : 0 }} FCFA</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-gray-300">
                                        <span class="text-gray-900 font-medium">Montant TTC:</span>
                                        <span class="font-bold text-gray-900" id="preview-ttc">{{ isset($tva) ? number_format(1000 + ($tva->taux * 10), 0) : 1000 }} FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                            >
                                <i class="ri-save-line mr-2"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panneau d'informations -->
            <div class="space-y-6">
                <!-- Informations sur la TVA -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="ri-lightbulb-line text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">À savoir</h3>
                    </div>
                    <div class="space-y-4 text-sm text-gray-600">
                        <div class="flex items-start space-x-3">
                            <i class="ri-arrow-right-circle-line text-blue-500 mt-0.5 flex-shrink-0"></i>
                            <p>Ce taux s'applique automatiquement à toutes les nouvelles transactions</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="ri-arrow-right-circle-line text-blue-500 mt-0.5 flex-shrink-0"></i>
                            <p>Les transactions existantes ne sont pas affectées par ce changement</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="ri-arrow-right-circle-line text-blue-500 mt-0.5 flex-shrink-0"></i>
                            <p>Un taux de 0% désactive l'application de la TVA</p>
                        </div>
                    </div>
                </div>

                <!-- Historique des modifications -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ri-history-line text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Dernières modifications</h3>
                    </div>
                    <div class="space-y-3">
                        @if(isset($tva) && $tva->updated_at)
                            <div class="flex items-center space-x-3 text-sm">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium">{{ $tva->taux }}%</p>
                                    <p class="text-gray-500">{{ $tva->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic">Aucune modification enregistrée</p>
                        @endif
                    </div>
                </div>

                <!-- Aide rapide -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ri-question-line text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Besoin d'aide ?</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Pour plus d'informations sur la configuration de la TVA, consultez notre documentation.
                    </p>
                    <a href="#" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <i class="ri-external-link-line mr-2"></i>
                        Voir la documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Aperçu en temps réel du calcul
document.getElementById('taux').addEventListener('input', function() {
    const taux = parseFloat(this.value) || 0;
    const montantHT = 1000;
    const montantTVA = (montantHT * taux) / 100;
    const montantTTC = montantHT + montantTVA;
    
    document.getElementById('preview-rate').textContent = taux.toFixed(2);
    document.getElementById('preview-tva').textContent = Math.round(montantTVA) + ' FCFA';
    document.getElementById('preview-ttc').textContent = Math.round(montantTTC) + ' FCFA';
});
</script>
@endsection