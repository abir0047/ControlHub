<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ml-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:text-black overflow-hidden shadow-sm sm:rounded-lg">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            @if (!$orders->isEmpty())
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-600 dark:text-gray-200">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                User Name</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Order Name</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Amount</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                toBkash</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                fromBkash</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                transactionId</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Approve</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                                Reject</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr></tr>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->user_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->name }}</td>

                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->amount }}</td>

                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->status }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->toBkash }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->fromBkash }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->transactionId }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex justify-center">
                                                        <form action="{{ route('order.process') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name='id'
                                                                value="{{ $order->id }}" />
                                                            <button
                                                                class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded">Approve</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex justify-center">
                                                        <form action="{{ route('order.remove') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name='id'
                                                                value="{{ $order->id }}" />
                                                            <button
                                                                class="m-2 p-2 bg-gray-200 hover:bg-gray-300 rounded">Reject</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- More items... -->
                                    </tbody>
                                </table>
                                <div class="m-2 p-2">
                                    {{ $orders->links() }}
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
