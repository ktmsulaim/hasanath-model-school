@extends('layouts.guest')
@section('main')
    @include('layouts.navigation')
    <x-alert />
    <!-- Validation Errors -->
    <x-auth-validation-errors class="px-4 mb-4 text-center" :errors="$errors" />

    @include('front')


    <div x-data="{}" x-init="" class="block py-6 mx-auto text-black max-w-7xl sm:px-6 lg:px-8">

        <div class="px-3 py-3 my-6 text-center text-green-700 bg-green-100 border border-green-200 shadow rounded-xl">
            Your Application is submitted succesfully! Download Files below and submit during Admission Test.
        </div>

        <div class="my-6 bg-white shadow rounded-xl">
            <table class="w-full align-middle table-auto rounded-xl">
                <thead>
                    <tr class="bg-gray-50 rounded-t-xl">
                        <th class="px-4 py-2 rounded-t-xl" colspan="4">
                            Download Forms
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr class="hidden text-center border-t sm:table-row">
                        <td :class="{ 'hidden': !(localStorage.getItem('dfdfdfImageSetup') && atob(localStorage.getItem('dfdfdfImageSetup'))) }"
                            class="px-4 py-2" rowspan="2"><img class="w-full max-w-xs"
                                 :src="localStorage.getItem('dfdfdfImageSetup') ? atob(localStorage.getItem('dfdfdfImageSetup')) : false">
                        </td>
                        <td class="px-4 py-2 text-center">{{ $data->code }}/{{ $data->id }}/2025</td>
                        <td class="px-4 py-2">{{ $data->name }}</td>
                        <td class="px-4 py-2">{{ $data->address }}</td>
                    </tr>
                    <tr class="border-t sm:hidden" colspan="4">
                        <td class="px-4 py-2 text-center"><img class="block w-full max-w-xs mx-auto"
                                 :src="localStorage.getItem('dfdfdfImageSetup') ? atob(localStorage.getItem('dfdfdfImageSetup')) : false">
                        </td>
                    </tr>
                    <tr class="border-t sm:hidden" colspan="4">
                        <td class="px-4 py-2 text-center">{{ $data->code }}/{{ $data->id }}/2025</td>
                    </tr>
                    <tr class="border-t sm:hidden" colspan="4">
                        <td class="px-4 py-2">{{ $data->name }}</td>
                    </tr>
                    <tr class="hidden text-center border-t sm:table-row">
                        <td class="px-4 py-2"><a target="_blank"
                               class="inline-block px-4 py-2 my-1 font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-xl"
                               href="{{ route('applicationPrint', ['uuid' => $data->uuid]) }}">Application Form <i
                                   class="ml-2 fa fa-download"></i></a></td>
                    </tr>
                    <tr class="border-t sm:hidden" colspan="4">
                        <td class="px-4 py-2"><a target="_blank"
                               class="inline-block px-4 py-1 my-1 text-center text-white bg-blue-600 hover:bg-blue-500 rounded-xl"
                               href="{{ route('applicationPrint', ['uuid' => $data->uuid]) }}">Application Form <i
                                   class="ml-2 fa fa-download"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection
