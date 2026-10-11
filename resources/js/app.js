import './bootstrap';
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Uppercase Inputs
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('[data-uppercase]')
        .forEach((input) => {

            input.addEventListener('input', function () {

                const start = this.selectionStart;
                const end = this.selectionEnd;

                this.value = this.value.toUpperCase();

                if (start !== null && end !== null) {
                    this.setSelectionRange(start, end);
                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Title Case Inputs
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('[data-titlecase]')
        .forEach((input) => {

            input.addEventListener('blur', function () {

                this.value = this.value
                    .toLowerCase()
                    .replace(/\b\w/g, char =>
                        char.toUpperCase()
                    );

            });

        });

    /*
|--------------------------------------------------------------------------
| Live Password Validation
|--------------------------------------------------------------------------
*/

document.querySelectorAll('[data-password-check]')
    .forEach((passwordInput) => {

        const targetId = passwordInput.dataset.passwordCheck;
        const container = document.getElementById(targetId);

        if (!container) {
            return;
        }

        const minLength = container.querySelector('[data-rule="min"]');
        const uppercase = container.querySelector('[data-rule="uppercase"]');
        const lowercase = container.querySelector('[data-rule="lowercase"]');
        const number = container.querySelector('[data-rule="number"]');

        const updateRule = (element, valid) => {
            if (!element) {
                return;
            }

            element.classList.toggle('text-green-600', valid);
            element.classList.toggle('text-gray-400', !valid);
        };

        const validatePassword = () => {
            const value = passwordInput.value;

            updateRule(
                minLength,
                value.length >= 8
            );

            updateRule(
                uppercase,
                /[A-Z]/.test(value)
            );

            updateRule(
                lowercase,
                /[a-z]/.test(value)
            );

            updateRule(
                number,
                /[0-9]/.test(value)
            );
        };

        passwordInput.addEventListener(
            'input',
            validatePassword
        );

    });


/*
|--------------------------------------------------------------------------
| Live Password Confirmation
|--------------------------------------------------------------------------
*/

document.querySelectorAll('[data-password-confirm]')
    .forEach((confirmInput) => {

        const passwordId =
            confirmInput.dataset.passwordConfirm;

        const passwordInput =
            document.getElementById(passwordId);

        const messageId =
            confirmInput.dataset.matchMessage;

        const message =
            document.getElementById(messageId);

        if (!passwordInput || !message) {
            return;
        }

        const validateMatch = () => {

            if (confirmInput.value === '') {

                message.textContent = '';
                return;

            }

            if (
                confirmInput.value ===
                passwordInput.value
            ) {

                message.textContent =
                    'Passwords match.';

                message.className =
                    'text-xs mt-1 text-green-600';

            } else {

                message.textContent =
                    'Passwords do not match.';

                message.className =
                    'text-xs mt-1 text-red-600';

            }

        };

        confirmInput.addEventListener(
            'input',
            validateMatch
        );

        passwordInput.addEventListener(
            'input',
            validateMatch
        );

    });

});

/*
|--------------------------------------------------------------------------
| Reusable Live Regions
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    const regions = Array.from(
        document.querySelectorAll('[data-live-region]')
    );

    if (regions.length === 0) {
        return;
    }

    const interval = Number(
        document.body.dataset.liveInterval || 3000
    );

    let refreshing = false;
    let userInteracting = false;
    let interactionTimer = null;


    const markUserInteraction = () => {

        userInteracting = true;

        clearTimeout(interactionTimer);

        interactionTimer = setTimeout(() => {
            userInteracting = false;
        }, 1500);

    };


    document.addEventListener(
        'pointerdown',
        markUserInteraction,
        true
    );

    document.addEventListener(
        'keydown',
        markUserInteraction,
        true
    );

    document.addEventListener(
        'submit',
        markUserInteraction,
        true
    );


    const refreshLiveRegions = async () => {

        if (
            refreshing ||
            document.hidden ||
            userInteracting
        ) {
            return;
        }

        refreshing = true;

        try {

            const response = await fetch(
                window.location.href,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    cache: 'no-store',
                }
            );

            if (!response.ok) {
                throw new Error(
                    `Live refresh failed (${response.status})`
                );
            }


            const html = await response.text();

            const nextDocument =
                new DOMParser().parseFromString(
                    html,
                    'text/html'
                );


            /*
            |--------------------------------------------------------------------------
            | Do not replace DOM if user started interacting
            | while the request was still running.
            |--------------------------------------------------------------------------
            */

            if (userInteracting || document.hidden) {
                return;
            }


            regions.forEach((currentRegion) => {

                const name =
                    currentRegion.dataset.liveRegion;

                const nextRegion =
                    nextDocument.querySelector(
                        `[data-live-region="${CSS.escape(name)}"]`
                    );

                if (!nextRegion) {
                    return;
                }


                /*
                | Avoid unnecessary DOM replacement.
                */

                if (
                    currentRegion.innerHTML !==
                    nextRegion.innerHTML
                ) {
                    currentRegion.innerHTML =
                        nextRegion.innerHTML;
                }

            });

        } catch (error) {

            console.error(
                'Live region refresh error:',
                error
            );

        } finally {

            refreshing = false;

        }

    };


    window.setInterval(
        refreshLiveRegions,
        interval
    );

});