<!doctype html>
<html lang="en">
<head>
    <title>Config Dumps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <style>
        [x-cloak] {
            display: none;
        }

        [type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
        }

        .form-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            display: inline-block;
            vertical-align: middle;
            background-origin: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            flex-shrink: 0;
            color: currentColor;
            background-color: #fff;
            border-color: #e2e8f0;
            border-width: 1px;
            border-radius: 0.25rem;
            height: 1.2em;
            width: 1.2em;
        }

        .form-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M5.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L7 8.586 5.707 7.293z'/%3e%3c/svg%3e");
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>

<div class="antialiased sans-serif bg-gray-200 min-h-screen" x-data="data()">

    <div class="container mx-auto py-6 px-4" x-cloak>
        <div class="flex pr-4 py-4 border-b mb-10">
            <h1 class="flex-1 text-4xl">Quad Config Dumps</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded items-center" @click="modalOpen = true">
                <span>Upload Dump</span>
            </button>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex-1 pr-4">
                <div class="relative md:w-1/3" hidden>
                    <input type="search" class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium" placeholder="Search...">
                    <div class="absolute top-0 left-0 inline-flex items-center p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <div class="shadow rounded-lg flex">
                    <div class="relative">
                        <button @click.prevent="open = !open" class="rounded-lg inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-semibold py-2 px-2 md:px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:hidden" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <path d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5" />
                            </svg>
                            <span class="hidden md:block">Display</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             class="z-40 absolute top-0 right-0 w-40 bg-white rounded-lg shadow-lg mt-12 -mr-1 block py-1 overflow-hidden">
                            <template x-for="heading in headings">
                                <label
                                    class="flex justify-start items-center text-truncate hover:bg-gray-100 px-4 py-2">
                                    <div class="text-teal-600 mr-3">
                                        <input type="checkbox" class="form-checkbox focus:outline-none focus:shadow-outline" checked @click="toggleColumn(heading.key)">
                                    </div>
                                    <div class="select-none text-gray-700" x-text="heading.value"></div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                <tr class="text-left">
                    <template x-for="heading in headings">
                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs"
                            x-text="heading.value" :x-ref="heading.key" :class="{ [heading.key]: true }"></th>
                    </template>
                </tr>
                </thead>
                <tbody>
                <template x-for="dump in dumps" :key="dump.id">
                    <tr>
                        <td class="border-dashed border-t border-gray-200 make">
                            <span class="text-gray-700 px-5 py-3 flex items-center" x-text="dump.make"></span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 model">
                            <span class="text-gray-700 px-5 py-3 flex items-center" x-text="dump.model"></span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 firmwareVersion">
                            <span class="text-gray-700 px-5 py-3 flex items-center" x-text="dump.firmwareVersion"></span>
                        </td>
                        <td class="border-dashed border-t px-5 border-gray-200 downloadURL">
                            <a :href="'download.php?id='+dump.id" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                            </a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" x-show="modalOpen">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline" @click.away="modalOpen = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Upload Configuration Dump
                            </h3>
                            <div class="mt-2 w-full max-w-lg">
                                <form x-ref="modalForm" @submit="e => e.preventDefault">
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="make">
                                                Make
                                            </label>
                                            <input class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="make" type="text" :placeholder="dumps[0] && dumps[0].make" x-model="modalData.make" required autofocus>
                                            <p class="text-red-500 text-xs italic" hidden>Please fill out this field.</p>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="model">
                                                Model
                                            </label>
                                            <input class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-500 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="model" type="text" :placeholder="dumps[0] && dumps[0].model" x-model="modalData.model" required>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full px-3">
                                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="dump">
                                                Config Dump
                                            </label>
                                            <textarea class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="dump" x-model="modalData.dump" style="height: 200px; resize: vertical" required placeholder="# diff all

# version
# Betaflight ..."></textarea>
                                        </div>
                                    </div>
                                    <input x-ref="modalFormSubmit" type="submit" hidden>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="modalSubmit()">
                            Save
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5" @click="modalOpen = false">
                          Cancel
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>


    <script>
        function data() {
            return {
                headings: [
                    {
                        'key': 'make',
                        'value': 'Make'
                    },
                    {
                        'key': 'model',
                        'value': 'Model'
                    },
                    {
                        'key': 'firmwareVersion',
                        'value': 'Firmware'
                    },
                    {
                        'key': 'downloadURL',
                        'value': 'Download'
                    }
                ],
                dumps: <?= file_exists(__DIR__ . '/dumps.json') ? file_get_contents(__DIR__ . '/dumps.json'): '[]' ?>,
                open: false,
                modalOpen: false,
                modalData: {
                    make: '',
                    model: '',
                    dump: '',
                },
                modalSubmit() {
                    if (this.$refs.modalForm.checkValidity() === false) {
                        this.$refs.modalFormSubmit.click();
                        return false;
                    }

                    fetch('/upload.php', {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        redirect: 'follow',
                        body: JSON.stringify(this.modalData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success === true) {
                            this.dumps.push(data.data)
                        }
                        this.modalOpen = false;
                        this.modalData.make = '';
                        this.modalData.model = '';
                        this.modalData.dump = '';
                        this.$refs.modalForm.reset();
                    });
                },


                toggleColumn(key) {
                    // Note: All td must have the same class name as the headings key!
                    let columns = document.querySelectorAll('.' + key);

                    console.log(this.$refs);

                    if (this.$refs[key].classList.contains('hidden') && this.$refs[key].classList.contains(key)) {
                        columns.forEach(column => {
                            column.classList.remove('hidden');
                        });
                    } else {
                        columns.forEach(column => {
                            column.classList.add('hidden');
                        });
                    }
                },
            }
        }
    </script>
</div>

</body>
</html>