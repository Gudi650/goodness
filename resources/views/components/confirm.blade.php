<div id="confirmBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-sm mx-4 p-6">
    <!-- Icon -->
    <div class="flex justify-center mb-4">
      <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-10S17.523 3 12 3z"/></svg>
      </div>
    </div>

    <!-- Title -->
    <h3 id="confirmTitle" class="text-base font-semibold text-slate-800 text-center mt-3 font-display"></h3>

    <!-- Message -->
    <p id="confirmMessage" class="text-sm text-slate-500 text-center mt-1"></p>

    <!-- Buttons -->
    <div class="flex gap-3 justify-center mt-5">
      <button onclick="closeConfirm()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
        Cancel
      </button>
      <button onclick="confirmAction()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors">
        Confirm
      </button>
    </div>
  </div>
</div>

<script>
  let confirmCallback = null;

  window.openConfirm = function(title, message, onConfirm) {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmBackdrop').classList.remove('hidden');
    confirmCallback = onConfirm;
  };

  window.closeConfirm = function() {
    document.getElementById('confirmBackdrop').classList.add('hidden');
    confirmCallback = null;
  };

  window.confirmAction = function() {
    if (confirmCallback) {
      confirmCallback();
    }
    closeConfirm();
  };

  // Close confirm on backdrop click
  document.getElementById('confirmBackdrop').addEventListener('click', function(e) {
    if (e.target === this) {
      closeConfirm();
    }
  });
</script>
