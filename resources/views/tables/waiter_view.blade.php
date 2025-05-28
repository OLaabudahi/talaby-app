<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Table Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Table Statuses</h3>

                    @if ($tables->isEmpty())
                        <p>No tables are set up yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($tables as $table)
                                <div class="border p-4 rounded-lg shadow-sm
                                    @if($table->status == 'available') border-green-300 bg-green-50
                                    @elseif($table->status == 'occupied') border-red-300 bg-red-50
                                    @else border-yellow-300 bg-yellow-50 @endif">
                                    <h4 class="text-xl font-semibold text-gray-800">{{ $table->table_number }}</h4>
                                    <p class="mt-2 text-gray-600">Status:
                                        <span class="font-bold
                                            @if($table->status == 'available') text-green-700
                                            @elseif($table->status == 'occupied') text-red-700
                                            @else text-yellow-700 @endif">
                                            {{ ucfirst($table->status) }}
                                        </span>
                                    </p>
                                    {{-- You might add a link here to create an order for an available table --}}
                                    @if ($table->status == 'available')
                                        <a href="{{ route('orders.create', ['table_id' => $table->id]) }}" class="mt-4 inline-block text-blue-600 hover:underline text-sm">Create Order for this Table</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>