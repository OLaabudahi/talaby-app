<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">All Restaurant Orders</h3>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($orders->isEmpty())
                        <p>No orders placed yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waiter</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->table->table_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($order->status == 'pending') bg-orange-100 text-orange-800
                                                    @elseif($order->status == 'served') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($order->total_price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('M d, H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                                @if ($order->status !== 'served' && $order->status !== 'canceled')
                                                    <form action="{{ route('orders.changeStatus', $order->id) }}" method="POST" class="inline-block ml-4">
                                                        @csrf
                                                        <input type="hidden" name="status" value="served">
                                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Mark this order as served?');">Mark Served</button>
                                                    </form>
                                                    <form action="{{ route('orders.changeStatus', $order->id) }}" method="POST" class="inline-block ml-4">
                                                        @csrf
                                                        <input type="hidden" name="status" value="canceled">
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Cancel this order?');">Cancel</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>