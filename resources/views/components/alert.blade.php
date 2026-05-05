@props([
    'type' => null,
    'message' => null,
    'title' => null,
    'dismissible' => true,
    'position' => 'bottom-right',
    'autoDismiss' => true,
    'duration' => 4000,
])

@php
    $alertTypes = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'text' => 'text-green-800',
            'title' => 'Success',
            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-800',
            'title' => 'Error',
            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
        ],
        'warning' => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'text' => 'text-amber-800',
            'title' => 'Warning',
            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'title' => 'Info',
            'icon' => '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
        ],
    ];

    $positions = [
        'top-right' => 'top-6 right-6',
        'top-left' => 'top-6 left-6',
        'bottom-right' => 'bottom-6 right-6',
        'bottom-left' => 'bottom-6 left-6',
        'top-center' => 'top-6 left-1/2 -translate-x-1/2',
        'bottom-center' => 'bottom-6 left-1/2 -translate-x-1/2',
    ];

    $resolvedType = $type ?: (session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info')));
    $config = $alertTypes[$resolvedType] ?? $alertTypes['info'];
    $containerPosition = $positions[$position] ?? $positions['bottom-right'];

    $sessionMessage = session('success') ?? session('error') ?? session('warning') ?? session('info') ?? null;
    $renderMessage = $message ?? $sessionMessage;
    $renderTitle = $title ?? $config['title'];
    $shouldRenderStatic = filled($renderMessage);
@endphp

@if($shouldRenderStatic)
  <div class="fixed {{ $containerPosition }} z-[60] w-full max-w-sm px-4 pointer-events-none">
    <div data-alert-static class="flex items-start gap-3 rounded-xl border px-4 py-3 shadow-lg {{ $config['bg'] }} {{ $config['border'] }} {{ $config['text'] }} pointer-events-auto">
            {!! $config['icon'] !!}
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold leading-5">{{ $renderTitle }}</p>
                <p class="mt-1 text-sm leading-5">{{ $renderMessage }}</p>
            </div>
            @if($dismissible)
                <button type="button" onclick="this.closest('[data-alert-static]').remove()" class="flex-shrink-0 text-current hover:opacity-70 transition-opacity" aria-label="Dismiss alert">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            @endif
        </div>
    </div>
@endif

<div id="alertContainer" class="fixed bottom-6 right-6 z-[70] flex flex-col gap-2 pointer-events-none"></div>

<script>
  window.showAlert = function(type, message, options = {}) {
    const container = document.getElementById('alertContainer');
    if (!container) return;

    const alertConfig = {
      success: {
        bg: 'bg-green-50',
        border: 'border-green-200',
        text: 'text-green-800',
        title: 'Success',
        icon: '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
      },
      error: {
        bg: 'bg-red-50',
        border: 'border-red-200',
        text: 'text-red-800',
        title: 'Error',
        icon: '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
      },
      warning: {
        bg: 'bg-amber-50',
        border: 'border-amber-200',
        text: 'text-amber-800',
        title: 'Warning',
        icon: '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
      },
      info: {
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        text: 'text-blue-800',
        title: 'Info',
        icon: '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
      }
    };

    const config = alertConfig[type] || alertConfig.info;
    const alertId = 'alert-' + Date.now();
    const duration = Number(options.duration ?? @json($duration));
    const title = options.title || config.title;
    const dismissible = options.dismissible !== false;

    const alertHTML = `
      <div id="${alertId}" class="flex items-start gap-3 px-4 py-3 rounded-xl shadow-lg text-sm max-w-sm ${config.bg} ${config.border} border ${config.text} pointer-events-auto transition-all duration-300 translate-y-0 opacity-100">
        ${config.icon}
        <div class="flex-1 min-w-0">
          <p class="font-semibold leading-5">${title}</p>
          <p class="mt-1 leading-5">${message}</p>
        </div>
        ${dismissible ? `<button type="button" onclick="dismissAlert('${alertId}')" class="flex-shrink-0 text-current hover:opacity-70 transition-opacity" aria-label="Dismiss alert"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>` : ''}
      </div>
    `;

    container.insertAdjacentHTML('beforeend', alertHTML);

    const alertElement = document.getElementById(alertId);
    if (!alertElement) return;

    if (duration > 0) {
      const timeout = setTimeout(() => {
        dismissAlert(alertId);
      }, duration);
      alertElement.dataset.timeout = String(timeout);
    }
  };

  window.dismissAlert = function(alertId) {
    const alertElement = document.getElementById(alertId);
    if (alertElement) {
      if (alertElement.dataset.timeout) {
        clearTimeout(parseInt(alertElement.dataset.timeout, 10));
      }
      alertElement.classList.add('opacity-0', 'translate-y-2');
      setTimeout(() => alertElement.remove(), 250);
    }
  };
</script>
