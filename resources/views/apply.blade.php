@extends('layouts.guest')
@section('main')
    @include('layouts.navigation')
    <x-alert />
    <!-- Validation Errors -->
    <x-auth-validation-errors class="px-4 mb-4 text-center" :errors="$errors" />

    <style type="text/css">
        textarea,
        input:not([type="email"]),
        select {
            text-transform: uppercase;
        }
    </style>

    @include('front')

    <div x-data="app()" x-cloak x-init="$nextTick(() => { window.setRules(); })"
         class="block py-6 mx-auto text-black max-w-7xl sm:px-6 lg:px-8">
        @csrf
        <div class="hidden sm:block" aria-hidden="true">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <form enctype="multipart/form-data" id="application_form" action="" method="POST">
            @csrf
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Details</h3>
                            <p class="mt-1 text-sm text-gray-600">Please provide correct information as per documents to
                                avoid mismatches.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 form-wrapper">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Applicant's
                                            Name</label>
                                        <input type="text" name="name" id="name" autocomplete="name"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 form-wrapper">
                                        <label class="block text-sm font-medium text-gray-700"> Photo </label>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-block w-16 h-16 overflow-hidden bg-gray-100 rounded-full">
                                                <svg x-show="!image" class="w-full h-full text-gray-300" fill="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path
                                                          d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <img :src="image" x-show="image" class="object-cover w-full h-full">
                                            </span>
                                            <label for="image"
                                                   class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <input type="file" id="image" name="image"
                                                       accept="image/jpg, image/jpeg, image/png" class="hidden"
                                                       @change="imageChange($event, 'image')" />
                                                <span x-text="image ? 'Change' : 'Select Photo'"></span>
                                            </label>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Passport Size Photo - Max size 512</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-4 form-wrapper">
                                        <label for="dob" class="block text-sm font-medium text-gray-700">Date of
                                            Birth</label>
                                        <input type="date" name="dob" id="dob" autocomplete="dob"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               min="2011-11-01" max="2013-04-30" pattern="\d{4}-\d{2}-\d{2}"
                                               @change="checkDate($event)" @input="checkDate($event)">
                                        <p class="my-2 text-sm text-green-600" x-text="date"></p>
                                        <p :class="{ 'text-gray-500': !dateError, 'text-red-500': dateError }"
                                           class="mt-2 text-xs">
                                            <b x-show="dateError">*</b> Must be between 01-Nov-2011 and 30-Apr-2013
                                        </p>
                                    </div>

                                    <div class="col-span-6 form-wrapper">
                                        <label for="guardian" class="block text-sm font-medium text-gray-700">Guardian's
                                            Name</label>
                                        <input type="text" name="guardian" id="guardian" autocomplete="guardian"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <!-- <div class="col-span-6 sm:col-span-3">
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <select id="country" name="country" autocomplete="country-name" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                  <option>United States</option>
                                  <option>Canada</option>
                                  <option>Mexico</option>
                                </select>
                              </div> -->

                                    <div class="col-span-6 form-wrapper">
                                        <label class="block text-sm font-medium text-gray-700"> Birth Certificate </label>
                                        <div
                                             class="flex justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg x-show="!bc && !bcPdf" class="w-12 h-12 mx-auto text-gray-400"
                                                     stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                                     aria-hidden="true">
                                                    <path
                                                          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <img :src="bc" x-show="bc" class="block w-64 h-auto mx-auto mb-4">
                                                <iframe x-show="bcPdf" :src="bcPdf ? bcPdf + '#toolbar=0' : false"
                                                        type="application/pdf" title="BC Pdf"
                                                        class="block w-64 h-auto mx-auto mb-4 overflow-y-visible"
                                                        style="min-height: 22rem;">
                                                    <a target="_blank" class="text-blue-500" :href="bcPdf">View selected
                                                        PDF</a>
                                                </iframe>
                                                <div class="flex items-center justify-center text-sm text-gray-600">
                                                    <label for="bc"
                                                           class="relative font-medium text-blue-600 bg-white rounded-md cursor-pointer hover:text-blue-500 focus-within:outline-none">
                                                        <span
                                                              x-text="bc || bcPdf ? 'Change the File' : 'Upload a file'"></span>
                                                        <input @change="imageChange($event, 'bc')"
                                                               accept=".pdf, image/jpg, image/jpeg, image/png" id="bc"
                                                               name="bc" type="file" class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG, JPEG up to 1MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-6 form-wrapper">
                                        <label class="block text-sm font-medium text-gray-700"> School Certificate / Mark
                                            Sheet (Required if Present*) </label>
                                        <div
                                             class="flex justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg x-show="!tc && !tcPdf" class="w-12 h-12 mx-auto text-gray-400"
                                                     stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                                     aria-hidden="true">
                                                    <path
                                                          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <img :src="tc" x-show="tc" class="block w-64 h-auto mx-auto mb-4">
                                                <iframe x-show="tcPdf" :src="tcPdf ? tcPdf + '#toolbar=0' : false"
                                                        type="application/pdf" title="TC Pdf"
                                                        class="block w-64 h-auto mx-auto mb-4 overflow-y-visible"
                                                        style="min-height: 22rem;">
                                                    <a target="_blank" class="text-blue-500" :href="tcPdf">View selected
                                                        PDF</a>
                                                </iframe>
                                                <div class="flex items-center justify-center text-sm text-gray-600">
                                                    <label for="tc"
                                                           class="relative font-medium text-blue-600 bg-white rounded-md cursor-pointer hover:text-blue-500 focus-within:outline-none">
                                                        <span
                                                              x-text="tc || tcPdf ? 'Change the File' : 'Upload a file'"></span>
                                                        <input @change="imageChange($event, 'tc')"
                                                               accept=".pdf, image/jpg, image/jpeg, image/png" id="tc"
                                                               name="tc" type="file" class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG, JPEG up to 1MB</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Communication Details</h3>
                            <p class="mt-1 text-sm text-gray-600">Enter your correct address and contact numbers.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="px-4 py-5 space-y-6 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">

                                    <input
                                           :value="village + (village.length && (po.length || ps.length) ? ', ' : '') + (po.length ? 'PO ' : '') + po + (po.length && ps.length ? ', ' : '') + (ps.length ? 'PS ' : '') + ps"
                                           type="hidden" name="address" id="address" autocomplete="address">

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="village" class="block text-sm font-medium text-gray-700">Village</label>
                                        <input type="text" x-model="village" name="village" id="village"
                                               autocomplete="village"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="po" class="block text-sm font-medium text-gray-700">Post Office</label>
                                        <input type="text" x-model="po" name="po" id="po" autocomplete="po"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="ps" class="block text-sm font-medium text-gray-700">PS</label>
                                        <input type="text" x-model="ps" name="ps" id="ps" autocomplete="ps"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="city" class="block text-sm font-medium text-gray-700">District</label>
                                        <input type="text" name="city" id="city" autocomplete="address-level2"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                        <input type="text" name="state" id="state" autocomplete="address-level1"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 lg:col-span-2 form-wrapper">
                                        <label for="postalcode" class="block text-sm font-medium text-gray-700">PIN
                                            code</label>
                                        <input type="number" name="postalcode" id="postalcode" autocomplete="postalcode"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               min="100000" max="999999" @input="slicer($event)" @change="slicer($event)">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 form-wrapper">
                                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile No. (
                                            Whatsapp ) without code</label>
                                        <input type="number" name="mobile" id="mobile" autocomplete="mobile"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               min="1000000000" max="9999999999" @input="slicer($event)"
                                               @change="slicer($event)">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3 form-wrapper">
                                        <label for="mobile2" class="block text-sm font-medium text-gray-700">Alternative
                                            Mobile No.</label>
                                        <input type="number" name="mobile2" id="mobile2" autocomplete="mobile2"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               min="1000000000" max="9999999999" @input="slicer($event)"
                                               @change="slicer($event)">
                                    </div>

                                    <div class="col-span-6 sm:col-span-4 form-wrapper">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" autocomplete="email"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Educational Details</h3>
                            <p class="mt-1 text-sm text-gray-600">Select most comfortable exam centre and date for the
                                student.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form action="#" method="POST">
                            <div class="overflow-hidden shadow sm:rounded-md">
                                <div class="px-4 py-5 space-y-6 bg-white sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">

                                        <fieldset class="col-span-6">
                                            <div>
                                                <legend class="text-base font-medium text-gray-900">Exam Centre</legend>
                                                <p class="text-sm text-gray-500">Select any exam center to appear for the
                                                    interview on the <b>specific date</b>.</p>
                                            </div>
                                            <div class="mt-4 space-y-4 form-wrapper">
                                                <template x-for="(centre, c) in examcentres">
                                                    <div class="flex items-start col-span-6">
                                                        <div class="flex items-center h-5">
                                                            <input :id="'examcentre' + c" name="examcentre" type="radio"
                                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded-full focus:ring-blue-500"
                                                                   :value="c + 1">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label :for="'examcentre' + c" class="font-medium text-gray-700"
                                                                   x-text="centre[0]"></label>
                                                            <p class="font-bold text-gray-500" x-text="centre[1]"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- <div x-show="show_centres" class="mt-4">
                                                <h3 class="pt-4 text-xl font-medium leading-6 text-gray-900">Exam Centres -
                                                    Details
                                                </h3>
                                                <div class="mt-2 font-semibold">Dubhri Exam Centre:</div>
                                                <p class="text-sm">
                                                    2292NO SORIAT MEMORIAL L.P SCHOOL. <br>
                                                    JHAGRARPAR PT-1(NEAR HOSPITAL CHECK GATE)<br>
                                                    OPP-J.M ENGLISH SCHOOL.<br>
                                                    DIST-DHUBRI(ASSAM)<br>
                                                    Mobile no of Head teacher-9101381392
                                                </p>
                                                <div class="mt-2 font-semibold">Guwahaty Exam Centre:</div>
                                                <p class="text-sm">
                                                    Katahbari ME SCHOOL. <br> ( Near balabhadra mandir). <br> Via Garchuk
                                                    Chariali. Guwahati
                                                    <br> Cont. 9678886786/ 7002514831.
                                                </p>

                                                <div class="mt-2 font-semibold">Goraimari Exam Centre Details:</div>
                                                <p class="text-sm">
                                                    College Masjid, Garoimari <br>
                                                    PS: Chaygaon <br>
                                                    DT: Kamrup Rural <br>
                                                    Mob: 91012 63773 <br>
                                                    70352 42607
                                                </p>

                                                <div class="mt-2 font-semibold">Bongaigaon exam centre:</div>
                                                <p class="text-sm">
                                                    2 NO. JARAGURI HAFIZIYA MADRASSA <br>
                                                    NEAR 2 NO. JARAGURI JAMMA MASJID <br>
                                                    SIDAL SATI <br>
                                                    MANIKPUR <br>
                                                    CONTACT: 88128 96175 Rakibul Islam (President)
                                                </p>

                                                <div class="mt-2 font-semibold">Karupetia Exam Centre:</div>
                                                <p class="text-sm">
                                                    Bright Learning Goal Senior Secondary School (BLG College) <br>
                                                    Address: Balogorah (Kharupetia College Turning), <br> Kharupetia,
                                                    Darrang,
                                                    Assam <br>
                                                    Contd. No: +916002944595
                                                </p>

                                            </div>

                                            <button class="mt-4 btn btn-green" type="button"
                                                    @click="show_centres = !show_centres">
                                                <span x-text="show_centres ? 'Hide' : 'View'"></span> Exam Centres
                                            </button> --}}

                                        </fieldset>

                                        <div class="col-span-6 py-2">
                                            <div class="border-t border-gray-200"></div>
                                        </div>

                                        <fieldset class="col-span-6">
                                            <div>
                                                <legend class="text-base font-medium text-gray-900">Did the student study in
                                                    Madrassa or Maktab?</legend>
                                                <p class="text-sm text-gray-500">Select any option.</p>
                                            </div>
                                            <div class="mt-4 space-y-4 form-wrapper">
                                                <template x-for="(option, o) in ['Yes', 'No']">
                                                    <div class="flex items-start col-span-6">
                                                        <div class="flex items-center h-5">
                                                            <input x-model="makthab" :id="'makthab' + o" name="makthab"
                                                                   type="radio" :value="option"
                                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded-full focus:ring-blue-500">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label :for="'makthab' + o" class="font-medium text-gray-700"
                                                                   x-text="option"></label>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                        </fieldset>

                                        <div class="col-span-6 sm:col-span-3 form-wrapper" x-show="makthab == 'Yes'">
                                            <label for="makthab_years" class="block text-base font-medium text-gray-900">If
                                                yes, how many years?</label>
                                            <input type="number" name="makthab_years" id="makthab_years"
                                                   autocomplete="makthab_years"
                                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Declaration</h3>
                            <p class="mt-1 text-sm text-gray-600">Please give correct and accurate information.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="overflow-hidden shadow sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6 form-wrapper">
                                <div class="grid grid-cols-6 gap-6">

                                    <div class="flex items-start col-span-6">
                                        <div class="flex items-center h-5">
                                            <input id="declare" name="declare" type="checkbox" value="yes"
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="declare" class="font-medium text-gray-700">I hereby declare that the
                                                above mentioned information are correct and true according to the best of my
                                                knowledge.</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="px-4 py-3 text-center bg-gray-50 sm:px-6">
                                <button type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Submit
                                    Application</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
            integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        function app() {
            return {
                show_centres: false,
                dateError: false,
                date: 'Not Selected',
                image: false,
                bc: false,
                bcPdf: false,
                tcPdf: false,
                tc: false,
                village: '',
                po: '',
                ps: '',
                makthab: '',
                examcentres: @json($examcentres),
                checkDate(e) {

                    this.dateError = true;

                    var start = moment('11-01-2011', 'MM-DD-YYYY');
                    var end = moment('04-30-2013', 'MM-DD-YYYY');
                    var date = e.target.value;

                    if (moment(date).isValid()) {
                        this.date = moment(date).format('DD-MMM-YYYY');
                    } else
                        this.date = 'Not Selected';

                    this.dateError = !moment(date).isBetween(start, end, undefined, '[]'); //true
                },
                slicer(e) {
                    if (e.target.value * 1 > e.target.max * 1) {
                        e.target.value = e.target.value.slice(0, e.target.max.length);
                    }
                },
                imageChange(e, obj) {
                    this[obj] = false;
                    if (obj != 'image')
                        this[obj + 'Pdf'] = false;
                    var file = e.target.files[0];
                    var error = false;
                    var fileTypes = {
                        image: ['image/jpg', 'image/jpeg', 'image/png'],
                        bc: ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf'],
                        tc: ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf']
                    };
                    var names = {
                        image: 'Image',
                        bc: 'Birth Certificate',
                        tc: 'Transfer Certificate'
                    };
                    var errors = {
                        image: 'jpg, jpeg, or png',
                        bc: 'pdf, jpg, jpeg, or png',
                        tc: 'pdf, jpg, jpeg, or png'
                    };
                    var sizes = {
                        image: 512,
                        bc: 1024,
                        tc: 1024
                    };
                    if (!fileTypes[obj].includes(file.type))
                        error = names[obj] + ' must be ' + errors[obj] + ' format!';
                    else if (file.size > sizes[obj] * 1024)
                        error = names[obj] + ' maximum size is ' + sizes[obj] + ' kb!';
                    if (error) {
                        alert(error);
                        e.target.value = '';
                        return false;
                    }
                    let fileReader = new FileReader();
                    fileReader.onload = (e) => {
                        let fileURL = fileReader.result;
                        this[obj] = fileURL;
                        if (obj != 'image') {
                            if (file.type == 'application/pdf') {
                                this[obj] = false;
                                this[obj + 'Pdf'] = fileURL;
                            }
                        } else {
                            localStorage.setItem('dfdfdfImageSetup', btoa(fileURL));
                            console.log(btoa(fileURL));
                        }
                    };
                    fileReader.readAsDataURL(file);
                }
            };
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script>
        class FormValidate {

            constructor($form, options, $next = false, $submit = true) {
                this.loadingAjax = false;
                $form = document.querySelector($form);
                $form.setAttribute('novalidate', true);
                this.form = $form;
                this.highlighted = false;
                var $this = this;
                if ($submit) {
                    $form.addEventListener("submit", function(ev) {
                        $this.checkSubmit(options, $next, ev);
                    });
                } else {
                    this.checkSubmit(options, $next);
                }
            }

            checkSubmit(options, $next, e = false) {
                if (e) e.preventDefault();
                this.constraints = options;
                this.callback = $next;
                this.count = 0;
                this.handleFormSubmit(this.form);
            }

            insertAfter(newNode, referenceNode) {
                referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
            }

            handleFormSubmit(form) {
                var formContents = validate.collectFormValues(form, {
                    trim: true
                });
                formContents = this.filterValues(formContents);
                var errors = validate(formContents, this.constraints);
                // then we update the form to reflect the results
                this.showErrors(form, errors || {});
                if (!errors) this.showSuccess();
            }

            filterValues(contentsArray) {
                var value;
                for (var i in contentsArray) {
                    value = contentsArray[i];
                    if (value != '' && typeof value == 'string' && value != null) {
                        contentsArray[i] = value.replace(/\s/gm, ' ').replace(/\s\s+/gm, ' ');
                    }
                    if (value === false) contentsArray[i] = null;
                }
                return contentsArray;
            }

            showSuccess() {
                if (this.callback)
                    this.callback(this.form, this);
                else this.ajaxSubmit(this.form);
            }

            showErrors(form, errors) {
                this.highlighted = false;
                var inputs = form.querySelectorAll("input[name], select[name], textarea[name]");
                for (var i = 0; inputs.length > i; i++) {
                    this.showErrorsForInput(inputs[i], errors && errors[inputs[i].getAttribute('name')] || errors[inputs[i].getAttribute('id')]);
                }
                if (this.highlighted)
                    this.highlighted.focus();
            }

            showErrorsForInput(input, errors) {
                // First we remove any old messages and resets the classes
                this.resetFormGroup(input);
                // If we have errors
                if (errors) {

                    if (!this.highlighted)
                        this.highlighted = input;

                    this.count++;
                    // we first mark the group has having errors
                    if (!(input).matches('[type="checkbox"]') && !(input).matches('[type="radio"]')) {
                        input.classList.add('focus:ring-red-500', 'focus:border-red-500');
                        input.classList.remove('focus:ring-blue-700', 'focus:border-blue-700');
                    }

                    // then we append all the errors
                    //for (var i = 0; i < errors.length; i++) {
                    this.addError(input, errors[0]); //errors[i]);
                    //}
                } else {
                    // otherwise we simply mark it as success
                    var inputId = input.getAttribute('name') || this.count;
                    var inputError = document.querySelector('[id="validator-sp----' + inputId + '"]');
                    if (inputError) inputError.style.display = 'none';
                }
            }

            ajaxSubmit(form) {
                if (this.loadingAjax) return;
                this.loadingAjax = true;
                document.getElementById('ajaxLoading').style.display = 'block';
                const token = '{{ csrf_token() }}';
                document.body.classList.add('h-screen', 'overflow-y-hidden');
                var formData = new FormData(form);
                fetch(form.action, {
                        credentials: "same-origin",
                        method: form.method || 'POST',
                        headers: {
                            'Accept': 'application/json',
                            "X-CSRF-Token": token
                        },
                        body: formData
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(text => {
                        console.log(text);
                        document.getElementById('ajaxLoading').style.display = 'none';
                        this.loadingAjax = false;
                        document.body.classList.remove('h-screen', 'overflow-y-hidden');
                        if (text.errors && Object.keys(text.errors).length) {
                            setTimeout(() => {
                                this.showErrors(this.form, text.errors);
                                // alert(text.errors[Object.keys(text.errors)[0]][0]);
                            }, 10);
                            return false;
                        } else if (text.status && text.status.trim() == 'success') {
                            window.location = text.redirect;
                        }
                    })
                    .catch(e => {
                        console.log(e);
                        document.getElementById('ajaxLoading').style.display = 'none';
                        this.loadingAjax = false;
                        document.body.classList.remove('h-screen', 'overflow-y-hidden');
                        setTimeout(() => {
                            alert('There is an error. Please retry again!');
                        }, 10);
                    });
            }

            resetFormGroup(input) {
                input.classList.remove('focus:ring-red-500', 'focus:border-red-500');
                input.classList.add('focus:ring-blue-700', 'focus:border-blue-700');
            }

            addError(input, error) {
                var inputId = input.getAttribute('name') || this.count;
                var inputError = document.querySelector('[id="validator-sp----' + inputId + '"]');
                if (!inputError) {
                    var newDiv = document.createElement('div');
                    newDiv.innerHTML = `<div class="flex items-center pt-3" id="validator-sp----${inputId}">
              <div class="mr-1 text-red-500 rounded-full">
              <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
              </div>
              <span class="text-sm font-medium text-red-500 error-message">${error}</span>
              </div>`;
                    // if ((input).matches('[type="checkbox"]') || (input).matches('[type="radio"]')) {
                    //     (input).closest('label').closest('.flex').closest('div').after(newDiv);
                    // } else {
                    //      (input).after(newDiv);

                    // }
                    input.closest('.form-wrapper').append(newDiv);
                } else {
                    inputError.querySelector('.error-message').innerHTML = error;
                    inputError.style.display = 'flex';
                }
            }

        }

        // Before using it we must add the parse and format functions
        // Here is a sample implementation using moment.js
        validate.extend(validate.validators.datetime, {
            // The value is guaranteed not to be null or undefined but otherwise it
            // could be anything.
            parse: function(value, options) {
                return +moment.utc(value);
            },
            // Input is a unix timestamp
            format: function(value, options) {
                var format = options.dateOnly ? "DD-MMM-YYYY" : "YYYY-MM-DD hh:mm:ss";
                return moment.utc(value).format(format);
            }
        });

        validate.validators.requiredIf = function(value, options, key, attributes, globals) {
            if (options.check(attributes) && (value == null || value == '' || typeof value === 'undefined' || (typeof value == 'string' && value.trim() == ''))) {
                return '^This field is required!';
            }
        };

        window.setRules = () => {
            var start = moment('11-01-2011', 'MM-DD-YYYY');
            var end = moment('04-30-2013', 'MM-DD-YYYY');
            var rules = {
                email: {
                    email: true,
                },
                dob: {
                    datetime: {
                        dateOnly: true,
                        earliest: start.utc(),
                        latest: end.utc().add(1, 'days'),
                        message: '^Date of Birth must be between 01-Nov-2011 and 30-Apr-2013'
                    }
                },
                examcentre: {
                    inclusion: {
                        within: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                        message: "^Please Select Any Option!"
                    }
                },
                "postalcode": {
                    numericality: {
                        onlyInteger: true,
                        greaterThanOrEqualTo: 100000,
                        lessThanOrEqualTo: 999999,
                        message: "^Please enter a valid postal code."
                    }
                },
                "mobile": {
                    numericality: {
                        onlyInteger: true,
                        greaterThanOrEqualTo: 1000000000,
                        lessThanOrEqualTo: 9999999999,
                        message: "^Please enter a valid 10 digit mobile no. without code."
                    }
                },
                "mobile2": {
                    numericality: {
                        onlyInteger: true,
                        greaterThanOrEqualTo: 1000000000,
                        lessThanOrEqualTo: 9999999999,
                        message: "^Please enter a valid 10 digit mobile no. without code."
                    }
                },
                "makthab_years": {
                    requiredIf: {
                        check: (a) => {
                            return a.makthab == 'Yes';
                        }
                    },
                    numericality: {
                        onlyInteger: true,
                        greaterThanOrEqualTo: 1,
                        lessThanOrEqualTo: 8,
                        message: "^Please enter a valid number."
                    }
                }
            };
            var presence = {
                presence: {
                    allowEmpty: false,
                    message: '^This field is required!'
                }
            };
            document.querySelector('#application_form').querySelectorAll('input:not([type="hidden"]), textarea, select').forEach(i => {
                if (i.name == 'tc') {
                    return false;
                }
                if (i.name == 'makthab_years') {
                    rules[i.name] = {
                        ...rules[i.name]
                    };
                } else {
                    rules[i.name] = {
                        ...rules[i.name],
                        ...presence
                    };
                }
            });
            var validated = new FormValidate('#application_form', rules);
        }
    </script>
@endsection
