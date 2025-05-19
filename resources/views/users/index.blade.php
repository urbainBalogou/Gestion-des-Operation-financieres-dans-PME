
  @extends('layouts.app')
  @section('contenu')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
  <!-- En-tête avec le titre et le bouton d'ajout -->
  <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-800 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-white">Gestion des utilisateurs</h1>
    <a href="{{ route('users.create') }}" class="bg-white text-blue-700 px-4 py-2 rounded-md shadow hover:bg-blue-50 transition-colors flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      Ajouter un utilisateur
    </a>
  </div>

  <!-- Message de notification -->
  <div id="notifications" class="px-6 pt-4">
    @if(session('success'))
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded flex items-center" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
        <button type="button" class="ml-auto" onclick="this.parentElement.remove()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    @endif
  </div>

  <!-- Barre de recherche et filtres -->
  <div class="px-6 py-4 flex flex-wrap gap-4">
    <div class="relative flex-grow max-w-md">
      <input type="text" id="searchUser" placeholder="Rechercher un utilisateur..." class="w-full px-4 py-2 border rounded-md pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
    </div>
    <select class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="">Tous les rôles</option>
      <option value="admin">Administrateur</option>
      <option value="user">Utilisateur</option>
    </select>
  </div>

  <!-- Tableau des utilisateurs -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            <div class="flex items-center">
              Nom
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400 cursor-pointer hover:text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            <div class="flex items-center">
              Email
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400 cursor-pointer hover:text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière connexion</th>
          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($users as $user)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-semibold">
                  {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                @if(isset($user->role))
                <div class="text-sm text-gray-500">{{ ucfirst($user->role) }}</div>
                @endif
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $user->email }}</div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
              Actif
            </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            @if(isset($user->last_login))
              {{ $user->last_login->format('d/m/Y H:i') }}
            @else
              --
            @endif
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end items-center space-x-2">
              <a href="{{ route('users.edit', $user->id) }}" title="Modifier" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
              </a>
              <a href="{{ route('users.show', $user->id) }}" title="Voir" class="text-gray-600 hover:text-gray-900 p-1 rounded hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
              </a>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(this)" title="Supprimer" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100 transition-colors focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="px-6 py-4 border-t border-gray-200">
    {{ $users->links() }}
  </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
    <div class="p-6">
      <div class="flex items-start">
        <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
          <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
          <p class="mt-2 text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
        </div>
      </div>
      <div class="mt-6 flex justify-end space-x-3">
        <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-800 focus:outline-none">Annuler</button>
        <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-white focus:outline-none">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Fonction pour le filtrage de la table
  document.getElementById('searchUser').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
      const name = row.querySelector('td:first-child').textContent.toLowerCase();
      const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      
      if (name.includes(searchTerm) || email.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  // Fonctions pour la modal de confirmation de suppression
  let currentForm = null;

  function confirmDelete(button) {
    currentForm = button.closest('form');
    document.getElementById('deleteModal').classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentForm = null;
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentForm) {
      currentForm.submit();
    }
    closeDeleteModal();
  });

  // Fermer les alertes automatiquement après 5 secondes
  setTimeout(() => {
    const alerts = document.querySelectorAll('#notifications > div');
    alerts.forEach(alert => {
      alert.style.transition = 'opacity 1s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 1000);
    });
  }, 5000);
</script>
@endsection
