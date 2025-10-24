@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Payments</h1>

    @auth
        <p class="mb-4">View your booking payments and transaction history.</p>
        <a href="{{ route('bookings.my') }}" class="bg-[#F37021] text-white px-6 py-3 rounded-lg">
            View My Bookings & Payments
        </a>
    @else
        <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-[#F37021] font-semibold">login</a> to view your payments.</p>
    @endauth
</div>
@endsection
