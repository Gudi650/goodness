<div id="modalBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
  <div id="modalCard" class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <h2 id="modalTitle" class="text-lg font-semibold text-slate-800 font-display"></h2>
      <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Body -->
    <div id="modal-body" class="mt-4"></div>

    <!-- Footer -->
    <div class="flex gap-3 justify-end mt-6">
      <button onclick="closeModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
        Cancel
      </button>
      <button onclick="submitModal()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium transition-colors">
        Submit
      </button>
    </div>
  </div>
</div>

<script>
  let modalCallback = null;

  window.openModal = function(title, bodyHTML, submitCallback = null) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modal-body').innerHTML = bodyHTML;
    document.getElementById('modalBackdrop').classList.remove('hidden');
    modalCallback = submitCallback;
  };

  window.closeModal = function() {
    document.getElementById('modalBackdrop').classList.add('hidden');
    document.getElementById('modal-body').innerHTML = '';
    modalCallback = null;
  };

  window.submitModal = function() {
    if (modalCallback) {
      modalCallback();
    }
    closeModal();
  };

  // Close modal on backdrop click
  document.getElementById('modalBackdrop').addEventListener('click', function(e) {
    if (e.target === this) {
      closeModal();
    }
  });
</script>
