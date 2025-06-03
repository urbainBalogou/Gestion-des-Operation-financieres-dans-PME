@extends('layouts.app')
@section('contenu')
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

@endsection