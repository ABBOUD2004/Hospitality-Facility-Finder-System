@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Services Management</h1>
        <a href="{{ route('service.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Add New Service
        </a>
    </div>

    @if ($message = Session::get('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ $message }}
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ $message }}
    </div>
    @endif

    @if($services->count() > 0)
    <div class="overflow-x-auto bg-white shadow-md rounded">
        <table class="w-full">
            <thead class="bg-gray-200 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-bold">ID</th>
                    <th class="px-6 py-3 text-left font-bold">Facility</th>
                    <th class="px-6 py-3 text-left font-bold">Service Name</th>
                    <th class="px-6 py-3 text-left font-bold">Description</th>
                    <th class="px-6 py-3 text-left font-bold">Price</th>
                    <th class="px-6 py-3 text-center font-bold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-3">{{ $service->id }}</td>
                    <td class="px-6 py-3">{{ $service->facility->name }}</td>
                    <td class="px-6 py-3 font-semibold">{{ $service->name }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $service->description ?? '-' }}</td>
                    <td class="px-6 py-3">${{ $service->price ?? '-' }}</td>
                    <td class="px-6 py-3 flex justify-center gap-3">
                        <a href="{{ route('service.show', $service->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View</a>
                        <a href="{{ route('service.edit', $service->id) }}" class="text-green-600 hover:text-green-800 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('service.destroy', $service->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" onclick="return confirm('Delete this service?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $services->links() }}
    </div>
    @else
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        No services found. <a href="{{ route('service.create') }}" class="font-bold underline">Add one now</a>
    </div>
    @endif
</div>
@endsection
