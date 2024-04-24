<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Section') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="my-10 w-full">
                <a href="{{ route('section.add') }}"
                    class="p-3 bg-gray-300 dark:text-black  hover:bg-gray-400 rounded">Add new</a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-600 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                            Id</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                            Name</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                            Edit</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr></tr>
                                    @foreach ($sectionData as $data)
                                        <tr>
                                            <td class="px-6 py-4 dark:text-black whitespace-nowrap">{{ $data->id }}
                                            </td>
                                            <td class="px-6 py-4 dark:text-black whitespace-nowrap">{{ $data->name }}
                                            </td>
                                            <td class="px-6 py-4 dark:text-black text-sm">
                                                <div class="flex justify-center">
                                                    <form action="{{ route('section.update.view', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button
                                                            class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded">Edit</button>
                                                    </form>
                                                    <form action="{{ route('section.delete', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- More items... -->
                                </tbody>
                            </table>
                            <div class="m-2 p-2">
                                {{ $sectionData->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
