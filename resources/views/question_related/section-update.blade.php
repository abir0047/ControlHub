<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Section Update') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Validation Errors -->
                <div class="flex justify-center">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                </div>
                <div class="w-full rounded-xl bg-white dark:text-black p-4 shadow-2xl shadow-white/40">
                    <div class="flex justify-center">
                        <form action="{{ route('section.update') }}" method="POST" class="w-full">
                            @csrf
                            <div class="flex flex-col w-full mt-5">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <label for="text" class="mb-2 font-semibold">Name</label>
                                <input type="text" id="name" name="name" value="{{ $data->name }}"
                                    class="w-full rounded-lg border bg-gray-100 border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" />
                            </div>
                            <div class="flex justify-center">
                                <button class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded mt-5">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
