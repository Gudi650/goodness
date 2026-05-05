@props([
    'title' => 'Confirm action',
    'message' => 'Are you sure you want to continue?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'danger',
])

@php
    $variants = [
        'danger' => [
            'iconBg' => 'bg-red-50',
            'iconText' => 'text-red-600',
            'button' => 'bg-red-600 hover:bg-red-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-10S17.523 3 12 3z"/>',
        ],
        'warning' => [
            'iconBg' => 'bg-amber-50',
            'iconText' => 'text-amber-500',
            'button' => 'bg-amber-600 hover:bg-amber-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-10S17.523 3 12 3z"/>',
        ],
        'success' => [
            'iconBg' => 'bg-emerald-50',
            'iconText' => 'text-emerald-600',
            'button' => 'bg-emerald-600 hover:bg-emerald-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z"/>',
        ],
        'info' => [
            'iconBg' => 'bg-sky-50',
            'iconText' => 'text-sky-600',
            'button' => 'bg-sky-600 hover:bg-sky-700',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9z"/>',
        ],
    ];

    $selected = $variants[$variant] ?? $variants['danger'];
@endphp

<div id="confirmBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-sm mx-4 p-6">
    <div class="flex justify-center mb-4">
      <div id="confirmIconWrap" class="w-12 h-12 {{ $selected['iconBg'] }} rounded-full flex items-center justify-center">
        <svg id="confirmIcon" class="w-6 h-6 {{ $selected['iconText'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $selected['icon'] !!}</svg>
      </div>
    </div>

    <h3 id="confirmTitle" class="text-base font-semibold text-slate-800 text-center mt-3 font-display">{{ $title }}</h3>
    <p id="confirmMessage" class="text-sm text-slate-500 text-center mt-1">{{ $message }}</p>

    <div class="flex gap-3 justify-center mt-5">
      <button type="button" onclick="closeConfirm()" id="confirmCancelBtn" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
        {{ $cancelText }}
      </button>
      <button type="button" onclick="confirmAction()" id="confirmActionBtn" class="px-4 py-2 {{ $selected['button'] }} text-white rounded-md text-sm font-medium transition-colors">
        {{ $confirmText }}
      </button>
    </div>
  </div>
</div>

<script>
  (function () {
    let confirmCallback = null;

    const variantMap = {
      danger: {
        iconBg: 'bg-red-50',
        iconText: 'text-red-600',
        button: 'bg-red-600 hover:bg-red-700',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-10S17.523 3 12 3z"/>',
      },
      warning: {
        iconBg: 'bg-amber-50',
        iconText: 'text-amber-500',
        button: 'bg-amber-600 hover:bg-amber-700',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-10S17.523 3 12 3z"/>',
      },
      success: {
        iconBg: 'bg-emerald-50',
        iconText: 'text-emerald-600',
        button: 'bg-emerald-600 hover:bg-emerald-700',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z"/>',
      },
      info: {
        iconBg: 'bg-sky-50',
        iconText: 'text-sky-600',
        button: 'bg-sky-600 hover:bg-sky-700',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9z"/>',
      },
    };

    function applyVariant(variant) {
      const selected = variantMap[variant] || variantMap.danger;
      const iconWrap = document.getElementById('confirmIconWrap');
      const icon = document.getElementById('confirmIcon');
      const actionBtn = document.getElementById('confirmActionBtn');

      if (iconWrap) {
        iconWrap.className = `w-12 h-12 ${selected.iconBg} rounded-full flex items-center justify-center`;
      }

      if (icon) {
        icon.className = `w-6 h-6 ${selected.iconText}`;
        icon.innerHTML = selected.icon;
      }

      if (actionBtn) {
        actionBtn.className = `px-4 py-2 ${selected.button} text-white rounded-md text-sm font-medium transition-colors`;
      }
    }

    window.openConfirm = function (titleOrOptions, message, onConfirm) {
      const options = typeof titleOrOptions === 'object' && titleOrOptions !== null
        ? titleOrOptions
        : { title: titleOrOptions, message, onConfirm };

      const titleEl = document.getElementById('confirmTitle');
      const messageEl = document.getElementById('confirmMessage');
      const backdropEl = document.getElementById('confirmBackdrop');
      const cancelBtn = document.getElementById('confirmCancelBtn');
      const actionBtn = document.getElementById('confirmActionBtn');

      if (titleEl) titleEl.textContent = options.title || 'Confirm action';
      if (messageEl) messageEl.textContent = options.message || 'Are you sure you want to continue?';
      if (cancelBtn) cancelBtn.textContent = options.cancelText || 'Cancel';
      if (actionBtn) actionBtn.textContent = options.confirmText || 'Confirm';

      applyVariant(options.variant || 'danger');

      if (backdropEl) backdropEl.classList.remove('hidden');
      confirmCallback = typeof options.onConfirm === 'function' ? options.onConfirm : null;
    };

    window.closeConfirm = function () {
      const backdropEl = document.getElementById('confirmBackdrop');
      if (backdropEl) backdropEl.classList.add('hidden');
      confirmCallback = null;
    };

    window.confirmAction = function () {
      if (confirmCallback) {
        confirmCallback();
      }
      closeConfirm();
    };

    const backdrop = document.getElementById('confirmBackdrop');
    if (backdrop && !backdrop.dataset.bound) {
      backdrop.dataset.bound = 'true';
      backdrop.addEventListener('click', function (e) {
        if (e.target === this) {
          closeConfirm();
        }
      });
    }
  })();
</script>
