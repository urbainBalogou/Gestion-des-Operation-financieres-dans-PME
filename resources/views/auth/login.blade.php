<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
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
        
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Connexion</h2>

    <form action="{{ route('login.submit')}}" method="POST" class="space-y-5">
         @csrf
      <!-- Champ nom d'utilisateur -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
          <i class="fas fa-user"></i>
        </span>
        <input type="text" name="username" placeholder="Nom d'utilisateur"
               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Champ mot de passe -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
          <i class="fas fa-lock"></i>
        </span>
        <input type="password" name="password" placeholder="Mot de passe"
               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Se souvenir de moi -->
      <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2 text-gray-700">
          <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600">
          <span>Se souvenir de moi</span>
        </label>
        <a href="#" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
      </div>

      <!-- Bouton de connexion -->
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
        Se connecter
      </button>
    </form>
  </div>

</body>
</html>
