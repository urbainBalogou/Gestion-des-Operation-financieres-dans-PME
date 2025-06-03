<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Transactions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#4F46E5",
              secondary: "#6366F1",
            },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      .transaction-table::-webkit-scrollbar {
      height: 8px;
      }
      .transaction-table::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
      }
      .transaction-table::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 4px;
      }
      .transaction-table::-webkit-scrollbar-thumb:hover {
      background: #ccc;
      }
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
      }
      input[type="number"] {
      -moz-appearance: textfield;
      }
      .custom-switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
      }
      .custom-switch input {
      opacity: 0;
      width: 0;
      height: 0;
      }
      .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #e5e7eb;
      transition: .4s;
      border-radius: 34px;
      }
      .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
      }
      input:checked + .slider {
      background-color: #4F46E5;
      }
      input:checked + .slider:before {
      transform: translateX(26px);
      }
      .custom-checkbox {
      position: relative;
      display: inline-block;
      width: 18px;
      height: 18px;
      }
      .custom-checkbox input {
      opacity: 0;
      width: 0;
      height: 0;
      }
      .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 18px;
      width: 18px;
      background-color: #fff;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      }
      .custom-checkbox input:checked ~ .checkmark {
      background-color: #4F46E5;
      border-color: #4F46E5;
      }
      .checkmark:after {
      content: "";
      position: absolute;
      display: none;
      }
      .custom-checkbox input:checked ~ .checkmark:after {
      display: block;
      }
      .custom-checkbox .checkmark:after {
      left: 6px;
      top: 2px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
      }
      .custom-radio {
      position: relative;
      display: inline-block;
      width: 18px;
      height: 18px;
      }
      .custom-radio input {
      opacity: 0;
      width: 0;
      height: 0;
      }
      .radio-mark {
      position: absolute;
      top: 0;
      left: 0;
      height: 18px;
      width: 18px;
      background-color: #fff;
      border: 1px solid #d1d5db;
      border-radius: 50%;
      }
      .custom-radio input:checked ~ .radio-mark {
      background-color: #fff;
      border-color: #4F46E5;
      }
      .radio-mark:after {
      content: "";
      position: absolute;
      display: none;
      }
      .custom-radio input:checked ~ .radio-mark:after {
      display: block;
      }
      .custom-radio .radio-mark:after {
      top: 4px;
      left: 4px;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #4F46E5;
      }
      .custom-range {
      -webkit-appearance: none;
      width: 100%;
      height: 6px;
      border-radius: 5px;
      background: #e5e7eb;
      outline: none;
      }
      .custom-range::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #4F46E5;
      cursor: pointer;
      }
      .custom-range::-moz-range-thumb {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #4F46E5;
      cursor: pointer;
      border: none;
      }
      .dropdown-content {
      display: none;
      position: absolute;
      background-color: white;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
      z-index: 1;
      border-radius: 8px;
      overflow: hidden;
      }
      .dropdown-content.show {
      display: block;
      }
    </style>
  </head>
  @if(session()->has('success'))
            <div id="flash-message" class="flash-message fixed top-8 right-8 z-50 max-w-md bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button class="flash-close-btn absolute top-2 right-2 p-1 text-green-700 hover:text-green-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
        
        @if(session()->has('error'))
            <div id="flash-error" class="flash-message fixed top-8 right-8 z-50 max-w-md bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
                <button class="flash-close-btn absolute top-2 right-2 p-1 text-red-700 hover:text-red-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
        <style>
        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        
        .flash-animate-in {
            animation: slideInRight 0.8s ease-out forwards, bounce 3s ease 0.8s;
        }
        
        .flash-animate-out {
            animation: slideOutRight 0.8s ease-in forwards;
        }
    </style>
    
    <!-- Script pour les animations et fermeture automatique des messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('#flash-message, #flash-error, #flash-warning');
            
            // Ajouter l'animation d'entrée à tous les messages
            flashMessages.forEach(function(message) {
                // Ajouter l'animation d'entrée
                message.classList.add('flash-animate-in');
                
                // Configurer la fermeture automatique après 6 secondes (3s d'animation + 3s d'affichage)
                setTimeout(function() {
                    if (message && message.parentNode) {
                        // Ajouter l'animation de sortie
                        message.classList.remove('flash-animate-in');
                        message.classList.add('flash-animate-out');
                        
                        // Supprimer le message une fois l'animation terminée
                        setTimeout(function() {
                            message.remove();
                        }, 800); // Durée de l'animation de sortie
                    }
                }, 6000);
            });
            
            // Configurer les boutons de fermeture pour utiliser l'animation
            const closeButtons = document.querySelectorAll('.flash-close-btn');
            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const message = this.closest('.flash-message');
                    if (message) {
                        message.classList.remove('flash-animate-in');
                        message.classList.add('flash-animate-out');
                        setTimeout(function() {
                            message.remove();
                        }, 800);
                    }
                });
            });
        });
    </script>
  <body class="bg-gray-50 font-sans">
    <!-- En-tête et Navigation -->
    <header class="bg-white shadow-sm">
      <div
        class="container mx-auto px-4 py-3 flex items-center justify-between"
      >
        <div class="flex items-center">
          <div class="font-['Pacifico'] text-2xl text-primary mr-10">logo</div>
          <nav class="hidden md:flex space-x-6">
            <a href="{{ route('home') }}" class="text-primary font-medium flex items-center">
              <div class="w-5 h-5 flex items-center justify-center mr-1">
                <i class="ri-dashboard-line"></i>
              </div>
              Dashboard
            </a>
            <a
              href="{{ route('liste_transactions') }}"
              data-readdy="true"
              class="text-gray-600 hover:text-primary flex items-center"
            >
              <div class="w-5 h-5 flex items-center justify-center mr-1">
                <i class="ri-exchange-funds-line"></i>
              </div>
              Transactions
            </a>
            <a
              href="{{ route('transactions.rapport') }}"
              class="text-gray-600 hover:text-primary flex items-center"
            >
              <div class="w-5 h-5 flex items-center justify-center mr-1">
                <i class="ri-file-chart-line"></i>
              </div>
              Rapports
            </a>
            @if(auth()->user()->remember_token)
    <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-primary flex items-center">
        <div class="w-5 h-5 flex items-center justify-center mr-1">
            <i class="ri-settings-3-line"></i>
        </div>
        Utilisateurs
    </a>
    <a href="{{ route('admin.tva.edit') }}" class="text-gray-600 hover:text-primary flex items-center">
        <div class="w-5 h-5 flex items-center justify-center mr-1">
            <i class="ri-settings-3-line"></i>
        </div>
        Taux de TVA
    </a>

@endif

          </nav>
        </div>
        <div class="flex items-center space-x-4">
          <!-- <div class="relative hidden md:block">
            <div
              class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            >
              <div
                class="w-5 h-5 flex items-center justify-center text-gray-400"
              >
                <i class="ri-search-line"></i>
              </div>
            </div>
            <input
              type="text"
              class="bg-gray-100 border-none text-sm rounded-full pl-10 pr-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-primary/20"
              placeholder="Rechercher une transaction..."
            />
          </div> -->
          <button
            id="newTransactionBtn"
            class="bg-primary text-white px-4 py-2 rounded-button whitespace-nowrap flex items-center">
            <div class="w-5 h-5 flex items-center justify-center mr-1">
              <i class="ri-add-line"></i>
            </div>
            Nouvelle Transaction
          </button>
          <a href="{{ route ('categories.index') }}">
          <button
            id="newCategorieBtn"
            class="bg-primary text-white px-4 py-2 rounded-button whitespace-nowrap flex items-center">
            <div class="w-5 h-5 flex items-center justify-center mr-1">
              <i class="ri-add-line"></i>
            </div>
            Nouvelle Catégorie
          </button>
          </a>
          <div
            class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full cursor-pointer"
          >
            <i class="ri-notification-3-line text-gray-600"></i>
          </div>
          <div
            class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full cursor-pointer md:hidden"
            id="mobileMenuBtn"
          >
            <i class="ri-menu-line text-gray-600"></i>
          </div>
        </div>
      </div>
      <!-- Menu mobile -->
      <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
        <nav class="container mx-auto px-4 py-3 flex flex-col space-y-3">
          <a href="{{ route('home') }}" class="text-primary font-medium flex items-center py-2">
            <div class="w-5 h-5 flex items-center justify-center mr-2">
              <i class="ri-dashboard-line"></i>
            </div>
            Dashboard
          </a>
          <a
            href="https://readdy.ai/home/8d76a887-7a23-4f40-9bb1-fbb1b70cdba9/988e3fcc-85b6-457d-a3c4-3b7bdc78aa88"
            data-readdy="true"
            class="text-gray-600 hover:text-primary flex items-center py-2"
          >
            <div class="w-5 h-5 flex items-center justify-center mr-2">
              <i class="ri-exchange-funds-line"></i>
            </div>
            Transactions
          </a>
          <a
            href="#"
            class="text-gray-600 hover:text-primary flex items-center py-2"
          >
            <div class="w-5 h-5 flex items-center justify-center mr-2">
              <i class="ri-file-chart-line"></i>
            </div>
            Rapports
          </a>
          <a
            href="#"
            class="text-gray-600 hover:text-primary flex items-center py-2"
          >
            <div class="w-5 h-5 flex items-center justify-center mr-2">
              <i class="ri-settings-3-line"></i>
            </div>
            Paramètres
          </a>
          <div class="relative mt-2">
            <div
              class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            >
              <div
                class="w-5 h-5 flex items-center justify-center text-gray-400"
              >
                <i class="ri-search-line"></i>
              </div>
            </div>
            <input
              type="text"
              class="bg-gray-100 border-none text-sm rounded-full pl-10 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-primary/20"
              placeholder="Rechercher une transaction..."
            />
          </div>
        </nav>
      </div>
    </header>
    <main class="container mx-auto px-4 py-6">
        @yield('contenu')
    </main>

    <!-- Modal Nouvelle Transaction -->
    <div
  id="transactionModal"
  class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden"
>
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h3 class="text-lg font-bold text-gray-800">Nouvelle Transaction</h3>
      <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
        <div class="w-6 h-6 flex items-center justify-center">
          <i class="ri-close-line"></i>
        </div>
      </button>
    </div>
    
    <!-- Contenu scrollable -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
      <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Type de transaction -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Type de transaction</label>
          <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <label class="flex items-center">
              <span class="custom-radio">
                <input type="radio" name="type" value="depot" checked />
                <span class="radio-mark"></span>
              </span>
              <span class="ml-2 text-sm text-gray-700">Dépôt</span>
            </label>
            <label class="flex items-center">
              <span class="custom-radio">
                <input type="radio" name="type" value="debit" />
                <span class="radio-mark"></span>
              </span>
              <span class="ml-2 text-sm text-gray-700">Retrait</span>
            </label>
          </div>
        </div>

        <!-- TVA applicable -->
        <div class="mb-4">
          <label class="flex items-center">
            <input
              type="checkbox"
              id="tva_applicable"
              name="tva_applicable"
              class="rounded border-gray-300 text-primary focus:ring-primary"
            />
            <span class="ml-2 text-sm text-gray-700">TVA applicable</span>
          </label>
        </div>

        <!-- Montants -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label for="amount_ht" class="block text-sm font-medium text-gray-700 mb-1">
              Montant HT
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-gray-500">FCFA</span>
              </div>
              <input
                type="number"
                id="amount_ht"
                name="amount_ht"
                step="0.01"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-12 p-2.5"
                placeholder="0.00"
                required
              />
            </div>
          </div>
          
          <div>
            <label for="amount_ttc" class="block text-sm font-medium text-gray-700 mb-1">
              Montant TTC
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-gray-500">FCFA</span>
              </div>
              <input
                type="number"
                id="amount_ttc"
                name="amount_ttc"
                step="0.01"
                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-12 p-2.5"
                placeholder="Calculé automatiquement"
                readonly
              />
            </div>
          </div>
        </div>

        <!-- Catégorie et Date -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
              Catégorie
            </label>
            <div class="relative">
              <select
                required
                id="category_id"
                name="category_id"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 pr-8 appearance-none"
              >
                <option value="">Choisir une catégorie</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->libelle }}</option>
                @endforeach
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                  <i class="ri-arrow-down-s-line"></i>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
              Date
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                  <i class="ri-calendar-line"></i>
                </div>
              </div>
              <input
                type="date"
                id="date"
                name="date"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5"
                required
              />
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description
          </label>
          <textarea
            id="description"
            name="description"
            rows="3"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 resize-none"
            placeholder="Description de la transaction..."
          ></textarea>
        </div>
      
        <!-- Pièce jointe -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Pièce jointe
          </label>
          <div class="flex items-center justify-center w-full">
            <label
              id="file-drop-zone"
              class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
            >
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <div class="w-10 h-10 flex items-center justify-center mb-3 text-gray-400">
                  <i class="ri-upload-cloud-line text-2xl"></i>
                </div>
                <p class="mb-2 text-sm text-gray-500 text-center">
                  <span class="font-medium">Cliquez pour télécharger</span>
                  <span class="hidden sm:inline"> ou glissez-déposez</span>
                </p>
                <p class="text-xs text-gray-500">
                  PDF, PNG, JPG (MAX. 10Mo)
                </p>
              </div>
              <input id="file-upload" name="attachment" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg" />
            </label>
          </div>
          
          <!-- Aperçu du fichier -->
          <div id="file-preview" class="hidden mt-3 p-3 bg-gray-50 rounded-lg border">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div id="file-icon" class="w-8 h-8 flex items-center justify-center text-gray-500">
                  <i class="ri-file-line"></i>
                </div>
                <div>
                  <p id="file-name" class="text-sm font-medium text-gray-700 truncate max-w-xs"></p>
                  <p id="file-size" class="text-xs text-gray-500"></p>
                </div>
              </div>
              <button
                type="button"
                id="remove-file"
                class="text-red-500 hover:text-red-700 p-1"
              >
                <i class="ri-close-line"></i>
              </button>
            </div>
            <!-- Aperçu image -->
            <div id="image-preview" class="hidden mt-3">
              <img id="preview-image" class="max-w-full h-32 object-contain rounded" alt="Aperçu" />
            </div>
          </div>
        </div>
      </form>
    </div>
    
    <!-- Footer -->
    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 px-6 py-4 border-t bg-gray-50">
      <button
        type="button"
        id="cancelTransactionBtn"
        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg whitespace-nowrap order-2 sm:order-1"
      >
        Annuler
      </button>
      <button
        type="submit"
        form="transactionForm"
        class="px-4 py-2 bg-primary text-white rounded-lg whitespace-nowrap order-1 sm:order-2"
      >
        Enregistrer
      </button>
    </div>
  </div>
</div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Menu mobile
        const mobileMenuBtn = document.getElementById("mobileMenuBtn");
        const mobileMenu = document.getElementById("mobileMenu");
        if (mobileMenuBtn && mobileMenu) {
          mobileMenuBtn.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
          });
        }
        // Modal Nouvelle Transaction
        const newTransactionBtn = document.getElementById("newTransactionBtn");
        const transactionModal = document.getElementById("transactionModal");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const cancelTransactionBtn = document.getElementById("cancelTransactionBtn");
        if (
          newTransactionBtn &&
          transactionModal &&
          closeModalBtn &&
          cancelTransactionBtn
        ) {
          newTransactionBtn.addEventListener("click", function () {
            transactionModal.classList.remove("hidden");
          });
          closeModalBtn.addEventListener("click", function () {
            transactionModal.classList.add("hidden");
          });
          cancelTransactionBtn.addEventListener("click", function () {
            transactionModal.classList.add("hidden");
          });
          transactionModal.addEventListener("click", function (e) {
            if (e.target === transactionModal) {
              transactionModal.classList.add("hidden");
            }
          });
        }
        // Formulaire de transaction
        // const transactionForm = document.getElementById("transactionForm");
        // if (transactionForm) {
        //   transactionForm.addEventListener("submit", function (e) {
        //     e.preventDefault();
        //     // Ici, vous pourriez ajouter le code pour traiter le formulaire
        //     alert("Transaction enregistrée avec succès !");
        //     transactionModal.classList.add("hidden");
        //   });
        // }
        // Définir la date du jour par défaut
        const dateInput = document.getElementById("date");
        if (dateInput) {
          const today = new Date();
          const year = today.getFullYear();
          const month = String(today.getMonth() + 1).padStart(2, "0");
          const day = String(today.getDate()).padStart(2, "0");
          dateInput.value = `${year}-${month}-${day}`;
        }
      });
      document.addEventListener("DOMContentLoaded", function () {
        // Graphique d'évolution du solde
        const balanceChart = document.getElementById("balanceChart");
        if (balanceChart) {
          const chart = echarts.init(balanceChart);
          const option = {
            animation: false,
            tooltip: {
              trigger: "axis",
              backgroundColor: "rgba(255, 255, 255, 0.8)",
              borderColor: "#e5e7eb",
              borderWidth: 1,
              textStyle: {
                color: "#1f2937",
              },
            },
            grid: {
              left: "3%",
              right: "3%",
              bottom: "3%",
              top: "3%",
              containLabel: true,
            },
            xAxis: {
              type: "category",
              boundaryGap: false,
              data: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin"],
              axisLine: {
                lineStyle: {
                  color: "#e5e7eb",
                },
              },
              axisLabel: {
                color: "#6b7280",
              },
            },
            yAxis: {
              type: "value",
              axisLine: {
                show: false,
              },
              axisLabel: {
                color: "#6b7280",
                formatter: function (value) {
                  return "€" + value.toLocaleString();
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#f3f4f6",
                },
              },
            },
            series: [
              {
                name: "Solde",
                type: "line",
                smooth: true,
                symbol: "none",
                lineStyle: {
                  width: 3,
                  color: "rgba(87, 181, 231, 1)",
                },
                areaStyle: {
                  color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                    {
                      offset: 0,
                      color: "rgba(87, 181, 231, 0.2)",
                    },
                    {
                      offset: 1,
                      color: "rgba(87, 181, 231, 0.01)",
                    },
                  ]),
                },
                data: [85000, 92000, 89000, 107000, 113000, 128745],
              },
            ],
          };
          chart.setOption(option);
          window.addEventListener("resize", function () {
            chart.resize();
          });
        }
        // Graphique de répartition des dépenses
        const expensesChart = document.getElementById("expensesChart");
        if (expensesChart) {
          const chart = echarts.init(expensesChart);
          const option = {
            animation: false,
            tooltip: {
              trigger: "item",
              backgroundColor: "rgba(255, 255, 255, 0.8)",
              borderColor: "#e5e7eb",
              borderWidth: 1,
              textStyle: {
                color: "#1f2937",
              },
              formatter: "{b}: {c} € ({d}%)",
            },
            legend: {
              orient: "vertical",
              right: "5%",
              top: "center",
              itemWidth: 10,
              itemHeight: 10,
              textStyle: {
                color: "#1f2937",
              },
            },
            series: [
              {
                name: "Dépenses",
                type: "pie",
                radius: ["40%", "70%"],
                center: ["40%", "50%"],
                avoidLabelOverlap: false,
                itemStyle: {
                  borderRadius: 8,
                  borderColor: "#fff",
                  borderWidth: 2,
                },
                label: {
                  show: false,
                },
                emphasis: {
                  label: {
                    show: false,
                  },
                },
                labelLine: {
                  show: false,
                },
                data: [
                  {
                    value: 8750,
                    name: "Salaires",
                    itemStyle: { color: "rgba(87, 181, 231, 1)" },
                  },
                  {
                    value: 5200,
                    name: "Loyer",
                    itemStyle: { color: "rgba(141, 211, 199, 1)" },
                  },
                  {
                    value: 3450,
                    name: "Fournitures",
                    itemStyle: { color: "rgba(251, 191, 114, 1)" },
                  },
                  {
                    value: 2100,
                    name: "Services",
                    itemStyle: { color: "rgba(252, 141, 98, 1)" },
                  },
                ],
              },
            ],
          };
          chart.setOption(option);
          window.addEventListener("resize", function () {
            chart.resize();
          });
        }
        // Graphique de comparaison dépôts vs retraits
        const comparisonChart = document.getElementById("comparisonChart");
        if (comparisonChart) {
          const chart = echarts.init(comparisonChart);
          const option = {
            animation: false,
            tooltip: {
              trigger: "axis",
              backgroundColor: "rgba(255, 255, 255, 0.8)",
              borderColor: "#e5e7eb",
              borderWidth: 1,
              textStyle: {
                color: "#1f2937",
              },
              axisPointer: {
                type: "shadow",
              },
            },
            legend: {
              data: ["Dépôts", "Retraits"],
              top: "0%",
              textStyle: {
                color: "#1f2937",
              },
            },
            grid: {
              left: "3%",
              right: "3%",
              bottom: "3%",
              top: "10%",
              containLabel: true,
            },
            xAxis: {
              type: "category",
              data: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin"],
              axisLine: {
                lineStyle: {
                  color: "#e5e7eb",
                },
              },
              axisLabel: {
                color: "#6b7280",
              },
            },
            yAxis: {
              type: "value",
              axisLine: {
                show: false,
              },
              axisLabel: {
                color: "#6b7280",
                formatter: function (value) {
                  return "€" + value.toLocaleString();
                },
              },
              splitLine: {
                lineStyle: {
                  color: "#f3f4f6",
                },
              },
            },
            series: [
              {
                name: "Dépôts",
                type: "bar",
                barWidth: "20%",
                itemStyle: {
                  color: "rgba(87, 181, 231, 1)",
                  borderRadius: [4, 4, 0, 0],
                },
                data: [25000, 32000, 28000, 35000, 38000, 42350],
              },
              {
                name: "Retraits",
                type: "bar",
                barWidth: "20%",
                itemStyle: {
                  color: "rgba(252, 141, 98, 1)",
                  borderRadius: [4, 4, 0, 0],
                },
                data: [18000, 25000, 22000, 18000, 25000, 27890],
              },
            ],
          };
          chart.setOption(option);
          window.addEventListener("resize", function () {
            chart.resize();
          });
        }
      });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  const tvaCheckbox = document.getElementById('tva_applicable');
  const amountHT = document.getElementById('amount_ht');
  const amountTTC = document.getElementById('amount_ttc');
  const fileUpload = document.getElementById('file-upload');
  const filePreview = document.getElementById('file-preview');
  const fileName = document.getElementById('file-name');
  const fileSize = document.getElementById('file-size');
  const fileIcon = document.getElementById('file-icon');
  const imagePreview = document.getElementById('image-preview');
  const previewImage = document.getElementById('preview-image');
  const removeFileBtn = document.getElementById('remove-file');
  const fileDropZone = document.getElementById('file-drop-zone');

  // Calcul automatique de la TVA
  function calculateTTC() {
    const htValue = parseFloat(amountHT.value) || 0;
    if (tvaCheckbox.checked && htValue > 0) {
      const ttcValue = htValue * 1.20; // TVA à 20%
      amountTTC.value = ttcValue.toFixed(2);
    } else {
      amountTTC.value = htValue.toFixed(2);
    }
  }

  tvaCheckbox.addEventListener('change', calculateTTC);
  amountHT.addEventListener('input', calculateTTC);

  // Gestion des fichiers
  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function getFileIcon(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    switch (extension) {
      case 'pdf':
        return 'ri-file-pdf-line';
      case 'jpg':
      case 'jpeg':
      case 'png':
        return 'ri-image-line';
      default:
        return 'ri-file-line';
    }
  }

  function showFilePreview(file) {
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    fileIcon.innerHTML = `<i class="${getFileIcon(file.name)}"></i>`;
    
    // Aperçu image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImage.src = e.target.result;
        imagePreview.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.classList.add('hidden');
    }
    
    filePreview.classList.remove('hidden');
  }

  fileUpload.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      showFilePreview(file);
    }
  });

  removeFileBtn.addEventListener('click', function() {
    fileUpload.value = '';
    filePreview.classList.add('hidden');
    imagePreview.classList.add('hidden');
  });

  // Drag & Drop
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    fileDropZone.addEventListener(eventName, preventDefaults, false);
  });

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  ['dragenter', 'dragover'].forEach(eventName => {
    fileDropZone.addEventListener(eventName, highlight, false);
  });

  ['dragleave', 'drop'].forEach(eventName => {
    fileDropZone.addEventListener(eventName, unhighlight, false);
  });

  function highlight(e) {
    fileDropZone.classList.add('border-primary', 'bg-primary/5');
  }

  function unhighlight(e) {
    fileDropZone.classList.remove('border-primary', 'bg-primary/5');
  }

  fileDropZone.addEventListener('drop', handleDrop, false);

  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
      fileUpload.files = files;
      showFilePreview(files[0]);
    }
  }
});
</script>
  </body>
</html>
