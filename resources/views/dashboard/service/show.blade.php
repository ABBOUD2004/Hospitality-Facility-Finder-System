@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Service Details</h1>

    <div class="bg-white shadow-md rounded p-6">
        <p class="mb-4"><strong>ID:</strong> {{ $service->id }}</p>
        <p class="mb-4"><strong>Facility:</strong> {{ $service->facility->name ?? 'N/A' }}</p>
        <p class="mb-4"><strong>Name:</strong> {{ $service->name }}</p>
        <p class="mb-4"><strong>Description:</strong> {{ $service->description ?? 'N/A' }}</p>
        <p class="mb-4"><strong>Price:</strong> ${{ $service->price ?? 'N/A' }}</p>

        <div class="flex gap-2 mt-6">
            <a href="{{ route('service.edit', $service->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('service.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>
</div>
@endsection
