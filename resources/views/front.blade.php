<style type="text/css">
    .animate-width::before {
        width: auto;
        min-height: 1px;
        content: '.';
        animation: widthed 4s infinite;
    }

    @keyframes widthed {
        40% {
            content: '..';
        }

        85% {
            content: '...';
        }
    }

    [x-cloak] {
        display: none;
    }
</style>

<div id="ajaxLoading" style="display: none;" data-nosnippet
     class="fixed inset-0 z-50 flex items-center justify-center w-screen h-screen overflow-hidden text-3xl font-semibold text-gray-300 bg-gray-50 md:text-5xl">
    <div class="flex flex-col items-center justify-center w-full h-full">
        <div class="absolute w-full text-center animate-pinged">
            <span class="relative inset-0 w-full animate-pulse">HASANATH GIRLS CAMPUS</span>
        </div>
        <div class="absolute w-full pt-20 text-base text-center text-gray-700">
            Sending Data<span class="relative animate-width"></span>
        </div>
    </div>
</div>

<div
     class="items-center justify-center hidden px-4 mx-auto mt-10 mb-6 text-2xl font-bold text-center text-black uppercase sm:flex">
    <img src="{{ asset('img/logo.png') }}" class="block w-20 mx-auto mb-4 ml-auto mr-2 text-center">
    <div class="ml-2 mr-auto">Hasanath Girls Campus
        <br>
        West Bengal
        <div class="text-lg font-medium">
            Tapan, Rampurhat, Birbhum, West Bengal - 731224<br>
            Contact No: 9562572431, 9605536676
        </div>
    </div>
</div>
<div class="flex justify-center my-3 mt-5">
    <h2 class="px-3 px-8 py-2 text-xl font-bold text-center text-white bg-gray-800 shadow-lg rounded-xl">Application
        Form</h2>
</div>
<h1 class="px-4 py-2 mt-2 text-xl text-center">Admission to 2025 - 26 Academic Year</h1>

<p class="px-4 py-2 my-2 text-lg text-center text-white bg-blue-600 shadow-lg">Already Registered? <a
       class="text-gray-300 hover:text-white hover:underline" href="{{ route('applications') }}">Search Your
        Application Form</a></p>
