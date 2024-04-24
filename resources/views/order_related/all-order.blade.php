<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Order List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:text-black overflow-hidden shadow-sm sm:rounded-lg">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @if (!$orderList->isEmpty())
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-600 dark:text-gray-200">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Order Name</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Deadline</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Remove Access</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr></tr>
                                        @foreach ($orderList as $singleOrder)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $singleOrder->name }}</td>

                                                <td class="px-6 py-4 whitespace-nowrap">{{ $singleOrder->deadline }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex justify-center">
                                                        <form action="{{ route('order.removeAccess') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name='id'
                                                                value="{{ $singleOrder->id }}" />
                                                            <button
                                                                class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded">Action</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- More items... -->
                                    </tbody>
                                </table>
                                <div class="m-2 p-2">
                                    {{ $orderList->links() }}
                                </div>
                            @else
                                <div class="m-2 p-2">
                                    No more new orders.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
