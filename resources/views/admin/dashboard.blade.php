@extends('layouts.guest')
@section('main')
    @include('layouts.navigation')
    <x-alert />

    @include('front')

    <div class="block py-6 mx-auto text-black max-w-7xl sm:px-6 lg:px-8">

        <div class="px-4 py-4 my-4 text-center bg-white shadow sm:px-6 rounded-xl">

            <div class="mt-4 mb-6">
                <h3 class="mb-3 text-2xl font-semibold text-gray-700">Export Data</h3>

                <form action="{{ route('export') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-8 py-2 text-white bg-green-500 rounded-lg hover:bg-green-400">
                        Export as Excel
                    </button>
                </form>
            </div>

            <h3 class="mb-2 text-2xl font-semibold text-gray-700">Delete All Applications</h3>
            <form method="POST" action="{{ route('destroy') }}"
                  @submit.prevent="confirm('You are about to delete all Applications! Are you sure?') && $event.target.submit()">
                @csrf
                <div class="flex items-center justify-center my-4 form-wrapper">
                    <div class="block w-full max-w-xl">
                        <label for="password" class="block text-sm font-medium text-left text-gray-700">Confirm
                            Password:</label>
                        <input required type="password" name="password" id="password" autocomplete="password"
                               class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-left text-gray-600">Every Application will be deleted and the reference
                            and roll numbers will be reset to 1.</p>
                    </div>
                </div>
                <button type="submit" class="px-8 py-2 text-white bg-red-600 rounded-lg hover:bg-red-500">
                    Confirm
                </button>
            </form>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <h3 class="mb-2 text-2xl font-semibold text-gray-700">Settings</h3>
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form method="POST" action="{{ route('settings') }}">
                @csrf
                <div class="flex items-center justify-center my-4 form-wrapper">
                    <div class="grid w-full max-w-xl grid-cols-2 gap-3">
                        <div>
                            <label for="starting_at" class="block text-sm font-medium text-left text-gray-700">Admission
                                Starting At:</label>
                            <input required type="date" id="starting_at" name="starting_at"
                                   value="{{ old('starting_at', $settings->starting_at ?? '') }}" autocomplete="starting_at"
                                   class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="ending_at" class="block text-sm font-medium text-left text-gray-700">Admission
                                Ending At:</label>
                            <input required type="date" id="ending_at" name="ending_at"
                                   value="{{ old('ending_at', $settings->ending_at ?? '') }}" autocomplete="ending_at"
                                   class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="starting_at" class="block text-sm font-medium text-left text-gray-700">Results
                                Publishing At:</label>
                            <input required type="date" id="results_starting_at" name="results_starting_at"
                                   value="{{ old('results_starting_at', $settings->results_starting_at ?? '') }}" autocomplete="results_starting_at"
                                   class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="ending_at" class="block text-sm font-medium text-left text-gray-700">Results Ending
                                At:</label>
                            <input required type="date" id="results_ending_at" name="results_ending_at"
                                   value="{{ old('results_ending_at', $settings->results_ending_at ?? '') }}" autocomplete="results_ending_at"
                                   class="block w-full max-w-xl mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <button type="submit" class="px-8 py-2 text-white bg-green-500 rounded-lg hover:bg-green-400">
                    Save Changes
                </button>
            </form>

        </div>

        <div class="my-6 bg-white shadow rounded-xl">
            @if (!((\Carbon\Carbon::parse($settings->ending_at) ?? '') >= today()))
                <form class="block" action="{{ route('status') }}" method="POST">
                    @csrf
            @endif
            <table class="w-full table-auto rounded-xl">
                <thead class="">
                    <tr class="bg-gray-50 rounded-t-xl">
                        <th class="px-4 py-2 rounded-t-xl" colspan="5">
                            Applications List
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $data)
                        <tr class="text-center border-t">
                            <td class="px-1 py-2 sm:px-4">{{ $data->code }}/{{ $data->id }}/2024</td>
                            <td class="px-1 py-2 sm:px-4">{{ $data->name }}</td>
                            @if ((\Carbon\Carbon::parse($settings->ending_at) ?? '') >= today())
                                <td
                                    class="px-1 py-2 text-center sm:px-4">
                                    <div class="flex flex-col items-center">
                                        <a target="_blank"
                                           class="block px-4 py-2 my-1 font-semibold text-white bg-blue-600 hover:bg-blue-500 w-max rounded-xl"
                                           href="{{ route('applicationPrint', ['uuid' => $data->uuid]) }}">Application<i
                                               class="ml-2 fa fa-download"></i></a>
                                        <form action="{{ route('delete', ['id' => $data->id]) }}" method="POST"
                                              @submit.prevent="confirm('Are you sure to delete this application?') && $event.target.submit()">
                                            @csrf
                                            <button type="submit"
                                                    class="block px-4 py-2 my-1 font-semibold text-white bg-red-600 hover:bg-red-500 w-max rounded-xl">Delete
                                                <i class="ml-1 fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            @else
                                <td class="px-1 py-2 sm:px-4">
                                    <input type="radio" name="id{{ $data->id }}" class="mr-2 text-red-400 focus:ring-red-400"
                                           value="0" @if (!$data->status) checked @endif>
                                    <input type="radio" name="id{{ $data->id }}"
                                           class="ml-2 text-green-500 focus:ring-green-400" value="1" @if ($data->status) checked @endif>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (!((\Carbon\Carbon::parse($settings->ending_at) ?? '') >= today()))
                <div class="flex items-center justify-center w-full px-4 py-4">
                    <button type="submit" class="px-8 py-2 text-white bg-green-500 rounded-lg hover:bg-green-400">
                        Save Changes
                    </button>
                </div>
                </form>
            @endif
        </div>

        {{ $applications->links() }}
    </div>
@endsection('main')
