{{-- resources/views/components/toast.blade.php --}}

@if (session('success'))
    <div
        class="app-toast app-toast-success"
        role="alert"
        data-toast
    >
        <div class="app-toast-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <div class="app-toast-body">
            <p class="app-toast-title">Success</p>
            <p class="app-toast-message">
                {{ session('success') }}
            </p>
        </div>

        <button
            type="button"
            class="app-toast-close"
            aria-label="Close notification"
            data-toast-close
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="app-toast-progress"></div>
    </div>
@endif


@if (session('error'))
    <div
        class="app-toast app-toast-error"
        role="alert"
        data-toast
    >
        <div class="app-toast-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <div class="app-toast-body">
            <p class="app-toast-title">Error</p>
            <p class="app-toast-message">
                {{ session('error') }}
            </p>
        </div>

        <button
            type="button"
            class="app-toast-close"
            aria-label="Close notification"
            data-toast-close
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="app-toast-progress"></div>
    </div>
@endif


@if (session('warning'))
    <div
        class="app-toast app-toast-warning"
        role="alert"
        data-toast
    >
        <div class="app-toast-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <div class="app-toast-body">
            <p class="app-toast-title">Warning</p>
            <p class="app-toast-message">
                {{ session('warning') }}
            </p>
        </div>

        <button
            type="button"
            class="app-toast-close"
            aria-label="Close notification"
            data-toast-close
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="app-toast-progress"></div>
    </div>
@endif


{{-- Laravel validation errors --}}
@if ($errors->any())
    <div
        class="app-toast app-toast-error"
        role="alert"
        data-toast
    >
        <div class="app-toast-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <div class="app-toast-body">
            <p class="app-toast-title">Please check your input</p>

            <p class="app-toast-message">
                {{ $errors->first() }}
            </p>

            @if ($errors->count() > 1)
                <p class="app-toast-more">
                    + {{ $errors->count() - 1 }} more
                    {{ Str::plural('error', $errors->count() - 1) }}
                </p>
            @endif
        </div>

        <button
            type="button"
            class="app-toast-close"
            aria-label="Close notification"
            data-toast-close
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="app-toast-progress"></div>
    </div>
@endif


<style>
    /* =========================================================
       TOAST
       ========================================================= */

    .app-toast {
        position: fixed;
        top: 24px;
        right: 24px;

        width: min(390px, calc(100vw - 32px));
        min-height: 82px;

        display: flex;
        align-items: flex-start;
        gap: 13px;

        padding: 16px 46px 18px 16px;

        background: var(--app-surface);
        border: 1px solid var(--app-border);
        border-radius: var(--app-radius-lg);

        box-shadow:
            0 20px 25px -5px rgba(15, 23, 42, 0.08),
            0 8px 10px -6px rgba(15, 23, 42, 0.06);

        overflow: hidden;

        z-index: 99999;

        opacity: 0;
        transform: translateX(35px);

        animation: appToastIn 0.35s ease forwards;
    }


    /* =========================================================
       ICON
       ========================================================= */

    .app-toast-icon {
        width: 38px;
        height: 38px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;
    }


    /* =========================================================
       CONTENT
       ========================================================= */

    .app-toast-body {
        flex: 1;
        min-width: 0;
        padding-top: 1px;
    }


    .app-toast-title {
        margin: 0 0 3px;

        font-size: 14px;
        line-height: 1.4;
        font-weight: 700;

        color: var(--app-text);
    }


    .app-toast-message {
        margin: 0;

        font-size: 13px;
        line-height: 1.5;
        font-weight: 400;

        color: var(--app-text-muted);

        word-break: break-word;
    }


    .app-toast-more {
        margin: 5px 0 0;

        font-size: 11px;
        font-weight: 600;

        color: var(--app-text-light);
    }


    /* =========================================================
       CLOSE BUTTON
       ========================================================= */

    .app-toast-close {
        position: absolute;
        top: 14px;
        right: 14px;

        width: 28px;
        height: 28px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border: none;
        border-radius: 7px;

        background: transparent;
        color: var(--app-text-light);

        cursor: pointer;

        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }


    .app-toast-close:hover {
        background: var(--app-industrial-50);
        color: var(--app-industrial-700);
    }


    .app-toast-close:focus-visible {
        outline: 2px solid var(--app-focus);
        outline-offset: 2px;
    }


    /* =========================================================
       SUCCESS
       ========================================================= */

    .app-toast-success {
        border-left: 4px solid var(--app-success-500);
    }


    .app-toast-success .app-toast-icon {
        color: var(--app-success-700);
        background: var(--app-success-50);
    }


    .app-toast-success .app-toast-progress {
        background: var(--app-success-500);
    }


    /* =========================================================
       ERROR
       ========================================================= */

    .app-toast-error {
        border-left: 4px solid var(--app-danger-500);
    }


    .app-toast-error .app-toast-icon {
        color: var(--app-danger-700);
        background: var(--app-danger-50);
    }


    .app-toast-error .app-toast-progress {
        background: var(--app-danger-500);
    }


    /* =========================================================
       WARNING
       ========================================================= */

    .app-toast-warning {
        border-left: 4px solid var(--app-safety-500);
    }


    .app-toast-warning .app-toast-icon {
        color: var(--app-safety-700);
        background: var(--app-safety-50);
    }


    .app-toast-warning .app-toast-progress {
        background: var(--app-safety-500);
    }


    /* =========================================================
       PROGRESS
       ========================================================= */

    .app-toast-progress {
        position: absolute;
        left: 0;
        bottom: 0;

        width: 100%;
        height: 3px;

        transform-origin: left;

        animation: appToastProgress 5s linear forwards;
    }


    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes appToastIn {
        from {
            opacity: 0;
            transform: translateX(35px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }


    @keyframes appToastOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(35px);
        }
    }


    @keyframes appToastProgress {
        from {
            transform: scaleX(1);
        }

        to {
            transform: scaleX(0);
        }
    }


    .app-toast-hide {
        animation: appToastOut 0.3s ease forwards;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 640px) {

        .app-toast {
            top: 16px;
            right: 16px;
            left: 16px;

            width: auto;
        }

    }


    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        .app-toast,
        .app-toast-hide,
        .app-toast-progress {
            animation-duration: 0.01ms !important;
        }

    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const toasts = document.querySelectorAll('[data-toast]');

        toasts.forEach(function (toast, index) {

            /*
             * If there are multiple toast messages,
             * move the next toast slightly lower.
             */
            toast.style.top = `${24 + (index * 100)}px`;

            /*
             * Automatically close after 5 seconds.
             */
            const timer = setTimeout(function () {
                removeToast(toast);
            }, 5000);


            /*
             * Manual X button.
             */
            const closeButton = toast.querySelector('[data-toast-close]');

            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    clearTimeout(timer);
                    removeToast(toast);
                });
            }

        });


        function removeToast(toast) {

            if (!toast || toast.classList.contains('app-toast-hide')) {
                return;
            }

            toast.classList.add('app-toast-hide');

            setTimeout(function () {
                toast.remove();
            }, 300);
        }

    });
</script>