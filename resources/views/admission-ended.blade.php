@extends('layouts.guest')
@section('main')
    @include('layouts.navigation')
    <x-alert />
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4 text-center px-4" :errors="$errors" />

    <div class="block max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 text-black">
      <div class="bg-red-100 shadow text-red-600 font-medium border border-red-200 p-4 rounded-xl">
        Sorry, It is not admission time.
      </div>
    </div>
    
@endsection