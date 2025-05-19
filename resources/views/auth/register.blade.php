@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-md">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">Inscription</h1>
            <p class="mt-2 text-sm text-gray-600">Créez votre compte</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6" id="registerForm">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}"
                            class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                        
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                        
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required 
                            class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
                        <p id="passwordStrength" class="mt-1 text-xs"></p>
                        
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation du mot de passe</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p id="passwordMatch" class="mt-1 text-xs"></p>
                    </div>
                </div>

                <div>
                    <label for="access_level" class="block text-sm font-medium text-gray-700">Niveau d'accès</label>
                    <div class="mt-1">
                        <select id="access_level" name="access_level" required
                            class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('access_level') border-red-500 @enderror">
                            <option value="normal" selected>Normal</option>
                            <option value="restricted">Restreint</option>
                        </select>
                        
                        @error('access_level')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" id="submitBtn" disabled
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    S'inscrire
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte? 
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Connectez-vous
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordStrengthText = document.getElementById('passwordStrength');
    const passwordMatchText = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registerForm');
    
    // Variables pour vérifier la validation
    let passwordValid = false;
    let passwordsMatch = false;
    
    // Fonction pour vérifier la force du mot de passe
    function checkPasswordStrength() {
        const password = passwordInput.value;
        
        // Réinitialiser l'état
        passwordValid = false;
        
        if (password.length === 0) {
            passwordStrengthText.textContent = '';
            passwordStrengthText.className = 'mt-1 text-xs';
            return;
        }
        
        // Vérifier la force du mot de passe
        const strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*.])(?=.{8,})");
        const mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");
        
        if (strongRegex.test(password)) {
            passwordStrengthText.textContent = 'Mot de passe fort';
            passwordStrengthText.className = 'mt-1 text-xs text-green-500';
            passwordValid = true;
        } else if (mediumRegex.test(password)) {
            passwordStrengthText.textContent = 'Mot de passe moyen - ajoutez des caractères spéciaux';
            passwordStrengthText.className = 'mt-1 text-xs text-yellow-500';
            passwordValid = false;
        } else {
            passwordStrengthText.textContent = 'Mot de passe faible - minimum 8 caractères avec majuscules, minuscules, chiffres et caractères spéciaux';
            passwordStrengthText.className = 'mt-1 text-xs text-red-500';
            passwordValid = false;
        }
        
        updateSubmitButton();
    }
    
    // Fonction pour vérifier la correspondance des mots de passe
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Réinitialiser l'état
        passwordsMatch = false;
        
        if (confirmPassword.length === 0) {
            passwordMatchText.textContent = '';
            passwordMatchText.className = 'mt-1 text-xs';
            return;
        }
        
        if (password === confirmPassword) {
            passwordMatchText.textContent = 'Les mots de passe correspondent';
            passwordMatchText.className = 'mt-1 text-xs text-green-500';
            passwordsMatch = true;
        } else {
            passwordMatchText.textContent = 'Les mots de passe ne correspondent pas';
            passwordMatchText.className = 'mt-1 text-xs text-red-500';
            passwordsMatch = false;
        }
        
        updateSubmitButton();
    }
    
    // Mettre à jour l'état du bouton de soumission
    function updateSubmitButton() {
        submitBtn.disabled = !(passwordValid && passwordsMatch);
    }
    
    // Ajouter les écouteurs d'événements
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength();
        if (confirmPasswordInput.value.length > 0) {
            checkPasswordMatch();
        }
    });
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    // Prévenir la soumission si les mots de passe ne sont pas validés
    form.addEventListener('submit', function(e) {
        if (!(passwordValid && passwordsMatch)) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });
});
</script>
@endsection