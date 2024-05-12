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
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" pattern="\d{4}-\d{2}-\d{2}">
                                    </div>

                                    <div class="col-span-6 form-wrapper">
                                        <label for="guardian" class="block text-sm font-medium text-gray-700">Guardian's
                                            Name</label>
                                        <input type="text" name="guardian" id="guardian" autocomplete="guardian"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    {{-- mother --}}
                                    <div class="col-span-6 form-wrapper">
                                        <label for="mother" class="block text-sm font-medium text-gray-700">Mother's
                                            Name</label>
                                        <input type="text" name="mother" id="mother" autocomplete="mother"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    {{-- how many brothers the applicant has --}}
                                    <div class="col-span-6 sm:col-span-3 form-wrapper">
                                        <label for="brothers" class="block text-sm font-medium text-gray-700">No. of
                                            Brothers</label>
                                        <input type="number" name="brothers" id="brothers" autocomplete="brothers"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    {{-- how many sisters the applicant has --}}
                                    <div class="col-span-6 sm:col-span-3 form-wrapper">
                                        <label for="sisters" class="block text-sm font-medium text-gray-700">No. of
                                            Sisters</label>
                                        <input type="number" name="sisters" id="sisters" autocomplete="sisters"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>

                                    {{-- applicant is orphan / destitute --}}
                                    <div class="col-span-6 form-wrapper">
                                        <label for="orphan" class="block text-sm font-medium text-gray-700">Is the
                                            applicant an Orphan or Destitute?</label>
                                        <select x-model="is_orphan" id="orphan" name="type" autocomplete="orphan"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="0">No</option>
                                            <option value="1">Orphan</option>
                                            <option value="2">Destitute</option>
                                        </select>
                                    </div>

                                    {{-- if applicant is orphan, dod of father --}}
                                    <div class="col-span-6 sm:col-span-3 form-wrapper" x-show="is_orphan == 1">
                                        <label for="dod_father" class="block text-sm font-medium text-gray-700">Date of Death of
                                            Father</label>
                                        <input :disabled="is_orphan != 1" type="date" name="dod_father" id="dod_father" autocomplete="dod_father"
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
                            <p class="mt-1 text-sm text-gray-600">Please provide correct information as per documents to
                                avoid mismatches.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form action="#" method="POST">
                            <div class="overflow-hidden shadow sm:rounded-md">
                                <div class="px-4 py-5 space-y-6 bg-white sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">

                                        {{-- in which class applicant is studying? --}}
                                        <div class="col-span-6 sm:col-span-3 form-wrapper">
                                            <label for="class" class="block text-sm font-medium text-gray-700">Class in
                                                which the applicant is studying</label>
                                            <input type="text" name="class" id="class" autocomplete="class"
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
                image: false,
                village: '',
                po: '',
                ps: '',
                is_orphan: 0,
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
                        image: ['image/jpg', 'image/jpeg', 'image/png']
                    };
                    var names = {
                        image: 'Image',
                    };
                    var errors = {
                        image: 'jpg, jpeg, or png',
                    };
                    var sizes = {
                        image: 512
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
            var msg;
            if ((msg = options.check(attributes)) && (value == null || value == '' || typeof value === 'undefined' || (typeof value == 'string' && value.trim() == ''))) {
                return msg;
            }
        };

        window.setRules = () => {
            var rules = {
                dob: {
                    datetime: {
                        dateOnly: true
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
                "dod_father": {
                    datetime: {
                        dateOnly: true
                    },
                    requiredIf: {
                        check: function(attributes) {
                            return attributes.type == 1 ? '^This field is required when applicant is orphan!' : false;
                        }
                    },
                    presence: false
                },
                "image": {
                    presence: {
                        allowEmpty: false,
                        message: '^Please select a photo!'
                    }
                },
            };
            var presence = {
                presence: {
                    allowEmpty: false,
                    message: '^This field is required!'
                }
            };
            document.querySelector('#application_form').querySelectorAll('input:not([type="hidden"]), textarea, select').forEach(i => {
                rules[i.name] = {
                    ...presence,
                    ...rules[i.name],
                };
            });
            console.log(rules);
            var validated = new FormValidate('#application_form', rules);
        }
    </script>
@endsection
