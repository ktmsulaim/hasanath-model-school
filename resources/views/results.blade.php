@extends('layouts.guest')
@section('main')
    @include('layouts.navigation')


    <style type="text/css">
        textarea,
        input:not([type="email"]),
        select {
            text-transform: uppercase;
        }
    </style>

    <div class="block py-6 mx-auto mt-12 text-center text-black max-w-7xl sm:px-6 lg:px-8">

        <h3 class="mb-2 text-2xl font-semibold text-gray-700">Find Your Result</h3>

        <x-alert />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="px-4 my-4 text-center" :errors="$errors" />

        <form method="POST" action="{{ route('results') }}">
            @csrf
            <div class="flex items-center justify-center my-4 form-wrapper">
                <div class="block w-full max-w-xl">
                    <label for="id" class="block text-sm font-medium text-left text-gray-700">Application Roll No.</label>
                    <input required type="number" name="id" id="id" autocomplete="id"
                           class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-1 text-sm text-left text-gray-600">Check your Hallticket for <b>Roll No.</b></p>
                </div>
            </div>
            <div class="flex items-center justify-center my-4 form-wrapper">
                <div class="block w-full max-w-xl">
                    <label for="dob" class="block text-sm font-medium text-left text-gray-700">Date of Birth</label>
                    <input required type="date" name="dob" id="dob" autocomplete="dob"
                           class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
            <button type="submit" class="px-8 py-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                Check Result
            </button>
        </form>

        @if (session('result'))
            @php $result = session('result'); @endphp

            <div class="my-4 bg-white card">
                <div class="card-body">
                    <div class="py-1 border-b">
                        Name: <b>{{ $result->name }}</b>
                    </div>
                    <div class="py-1 border-b">
                        Ref No: <b>{{ $result->code }}/{{ $result->id }}/2025</b>
                    </div>
                    <div class="py-1">
                        Status: <b class="{{ $result->status ? 'text-green-400' : 'text-red-500' }}">{!! $result->status ? 'Congratulations, Your are selected!' : 'Sorry, you are not selected!' !!}</b>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
