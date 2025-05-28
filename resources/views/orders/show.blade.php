<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details: ') . $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6 border-b pb-4">
                        <p><strong>Order ID:</strong> {{ $order->id }}</p>
                        <p><strong>Table:</strong> {{ $order->table->table_number }}</p>
                        <p><strong>Waiter:</strong> {{ $order->user->name }}</p>
                        <p><strong>Status:</strong>
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($order->status == 'pending') bg-orange-100 text-orange-800
                                @elseif($order->status == 'served') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
                        <p><strong>Order Placed:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>

                        @if ($order->status == 'pending' && Auth::user()->role === 'waiter')
                            <form action="{{ route('orders.changeStatus', $order->id) }}" method="POST" class="inline-block mt-4">
                                @csrf
                                <input type="hidden" name="status" value="served">
                                <x-primary-button class="bg-green-600 hover:bg-green-700">Mark as Served</x-primary-button>
                            </form>
                            <form action="{{ route('orders.changeStatus', $order->id) }}" method="POST" class="inline-block mt-4 ml-2">
                                @csrf
                                <input type="hidden" name="status" value="canceled">
                                <x-danger-button>Cancel Order</x-danger-button>
                            </form>
                        @elseif(Auth::user()->role === 'admin')
                            <form action="{{ route('orders.changeStatus', $order->id) }}" method="POST" class="inline-block mt-4">
                                @csrf
                                <x-input-label for="new_status" class="inline-block mr-2" :value="__('Change Status:')" />
                                <select id="new_status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Served</option>
                                    <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                <x-primary-button class="ml-2">Update</x-primary-button>
                            </form>
                        @endif
                    </div>

                    <h4 class="text-lg font-medium text-gray-900 mb-4">Order Items:</h4>
                    @if ($order->orderItems->isEmpty())
                        <p>No items in this order yet.</p>
                    @else
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        @if ($order->status == 'pending' && Auth::user()->role === 'waiter')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->menu->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($order->status == 'pending' && Auth::user()->role === 'waiter')
                                                    <form action="{{ route('orders.updateItem', [$order->id, $item->id]) }}" method="POST" class="inline-flex items-center">
                                                        @csrf
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                                    </form>
                                                @else
                                                    {{ $item->quantity }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->quantity * $item->price, 2) }}</td>
                                            @if ($order->status == 'pending' && Auth::user()->role === 'waiter')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form action="{{ route('orders.removeItem', [$order->id, $item->id]) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this item?');">Remove</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($order->status == 'pending' && Auth::user()->role === 'waiter')
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Add Items to Order:</h4>
                        <form action="{{ route('orders.addItem', $order->id) }}" method="POST" class="flex items-end space-x-4">
                            @csrf
                            <div class="flex-1">
                                <x-input-label for="menu_id" :value="__('Menu Item')" />
                                <select id="menu_id" name="menu_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select an Item</option>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->id }}">{{ $menu->name }} (${{ number_format($menu->price, 2) }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('menu_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="quantity" :value="__('Quantity')" />
                                <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" value="1" min="1" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <x-primary-button>
                                {{ __('Add Item') }}
                            </x-primary-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Note: You might need to create a `x-danger-button` component in `resources/views/components` for the red button. --}}
{{-- Example for x-danger-button: --}}
{{-- resources/views/components/danger-button.blade.php --}}
{{--
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
--}}