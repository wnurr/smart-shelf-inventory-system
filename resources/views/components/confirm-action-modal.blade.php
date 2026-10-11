{{-- ========================================================= --}}
{{-- REUSABLE CONFIRM ACTION MODAL --}}
{{-- ========================================================= --}}

<div
    x-data="confirmActionModal()"
    x-show="open"
    x-cloak
    x-on:open-confirm-modal.window="showModal($event.detail)"
    x-on:keydown.escape.window="closeModal()"
    class="
        fixed
        inset-0
        z-[9999]
        flex
        items-center
        justify-center
        px-4
    "
    style="display: none;"
>

    {{-- ===================================================== --}}
    {{-- BACKDROP --}}
    {{-- ===================================================== --}}

    <div
        x-show="open"

        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"

        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"

        @click="closeModal()"

        class="
            absolute
            inset-0
            bg-industrial-900/50
            backdrop-blur-sm
        "
    ></div>


    {{-- ===================================================== --}}
    {{-- MODAL --}}
    {{-- ===================================================== --}}

    <div
        x-show="open"

        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"

        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"

        @click.stop

        class="
            relative
            w-full
            max-w-md
            overflow-hidden
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-2xl
        "
    >

        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="p-6">

            <div class="flex items-start gap-4">


                {{-- ================================================= --}}
                {{-- DANGER ICON --}}
                {{-- ================================================= --}}

                <div
                    class="
                        flex
                        h-11
                        w-11
                        flex-shrink-0
                        items-center
                        justify-center
                        rounded-full
                        border
                        border-danger-100
                        bg-danger-50
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6 text-danger-600"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m9.303
                               3.376c.866 1.5-.217
                               3.374-1.948
                               3.374H4.645c-1.73
                               0-2.813-1.874-1.948-3.374
                               L10.052 3.374c.866-1.5
                               3.032-1.5 3.898 0
                               l7.353 12.752ZM12
                               15.75h.007v.008H12v-.008Z"
                        />
                    </svg>

                </div>


                {{-- ================================================= --}}
                {{-- TEXT --}}
                {{-- ================================================= --}}

                <div class="min-w-0 flex-1">

                    <h3
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                        x-text="title"
                    ></h3>

                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-500
                        "
                        x-text="message"
                    ></p>

                </div>


                {{-- ================================================= --}}
                {{-- CLOSE --}}
                {{-- ================================================= --}}

                <button
                    type="button"
                    @click="closeModal()"

                    class="
                        flex
                        h-8
                        w-8
                        flex-shrink-0
                        items-center
                        justify-center
                        rounded-lg
                        text-slate-400
                        transition-all
                        duration-200

                        hover:bg-industrial-50
                        hover:text-industrial-700

                        focus:outline-none
                        focus:ring-2
                        focus:ring-industrial-500
                        focus:ring-offset-1
                    "

                    aria-label="Close confirmation"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div
            class="
                flex
                justify-end
                gap-3
                border-t
                border-slate-100
                bg-slate-50
                px-6
                py-4
            "
        >

            {{-- Cancel --}}

            <button
                type="button"
                @click="closeModal()"

                class="
                    inline-flex
                    min-h-10
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-slate-300
                    bg-white
                    px-4
                    py-2
                    text-sm
                    font-medium
                    text-slate-600
                    shadow-sm
                    transition-all
                    duration-200

                    hover:border-industrial-300
                    hover:bg-industrial-50
                    hover:text-industrial-800

                    focus:outline-none
                    focus:ring-2
                    focus:ring-industrial-500
                    focus:ring-offset-2

                    active:bg-industrial-100
                "
            >
                Cancel
            </button>


            {{-- Confirm --}}

            <button
                type="button"
                @click="confirmAction()"

                class="
                    inline-flex
                    min-h-10
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-transparent
                    bg-danger-600
                    px-4
                    py-2
                    text-sm
                    font-semibold
                    text-white
                    shadow-sm
                    transition-all
                    duration-200

                    hover:bg-danger-700
                    hover:shadow-md

                    focus:outline-none
                    focus:ring-2
                    focus:ring-danger-500
                    focus:ring-offset-2

                    active:bg-danger-800
                "

                x-text="confirmText"
            ></button>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- REUSABLE MODAL SCRIPT --}}
{{-- ========================================================= --}}

<script>

    function confirmActionModal() {

        return {

            open: false,

            title: 'Confirm Action',

            message: 'Are you sure you want to continue?',

            confirmText: 'Confirm',

            targetForm: null,


            showModal(detail) {

                this.title =
                    detail.title ||
                    'Confirm Action';


                this.message =
                    detail.message ||
                    'Are you sure you want to continue?';


                this.confirmText =
                    detail.confirmText ||
                    'Confirm';


                this.targetForm =
                    detail.form;


                this.open = true;

            },


            closeModal() {

                this.open = false;

                this.targetForm = null;

            },


            confirmAction() {

                if (!this.targetForm) {
                    return;
                }


                const form = this.targetForm;


                this.closeModal();


                /*
                 * requestSubmit() would trigger the submit
                 * event again and reopen this confirmation modal.
                 *
                 * form.submit() performs the actual confirmed
                 * submission directly.
                 */

                form.submit();

            }

        };

    }


    /*
     * ---------------------------------------------------------
     * GLOBAL CONFIRMATION FORM LISTENER
     * ---------------------------------------------------------
     *
     * Any form with:
     *
     * class="js-confirm-form"
     *
     * automatically uses this modal.
     *
     */

    document.addEventListener('submit', function (event) {

        const form =
            event.target.closest('.js-confirm-form');


        if (!form) {
            return;
        }


        event.preventDefault();


        window.dispatchEvent(

            new CustomEvent(
                'open-confirm-modal',
                {
                    detail: {

                        form: form,

                        title:
                            form.dataset.confirmTitle ||
                            'Confirm Action',

                        message:
                            form.dataset.confirmMessage ||
                            'Are you sure you want to continue?',

                        confirmText:
                            form.dataset.confirmButton ||
                            'Confirm'

                    }
                }
            )

        );

    });

</script>