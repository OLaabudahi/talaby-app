@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Menu Item Details') }}</div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ __('Name:') }}</strong> {{ $menu->name }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Description:') }}</strong> {{ $menu->description ?? __('No description available.') }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Price:') }}</strong> {{ $menu->price }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Category:') }}</strong> {{ ucfirst($menu->category) }}
                    </div>
                    @if ($menu->image_path)
                        <div class="mb-3">
                            <strong>{{ __('Image:') }}</strong>
                            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" style="max-width: 300px;">
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>{{ __('Created At:') }}</strong> {{ $menu->created_at }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Updated At:') }}</strong> {{ $menu->updated_at }}
                    </div>

                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection