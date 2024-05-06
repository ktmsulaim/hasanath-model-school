@if (session('message'))
    <style type="text/css">
      .bg-emerald-500 {
          --tw-bg-opacity: 1;
          background-color: rgba(16,185,129,var(--tw-bg-opacity));
      }

      .bg-lightBlue-500 {
          --tw-bg-opacity: 1;
          background-color: rgba(14,165,233,var(--tw-bg-opacity));
      }

      .bg-amber-500 {
          --tw-bg-opacity: 1;
          background-color: rgba(245,158,11,var(--tw-bg-opacity));
      }
      [x-cloak] {
        display: none;
      }
    </style>
    <div x-data="{openNotify: false, type: '{{ session('type') ? : 'success' }}', open1: false, data: '{{ session('message') }}'}" x-init="() => { openNotify = true; open1 = true; setTimeout(function() { open1 = false; openNotify = false;  }, 3500) }" x-cloak x-show="openNotify"
        class="fixed sm:top-4 bottom-auto top-2 left-auto right-auto sm:right-4 flex justify-center flex-col space-y-2 max-w-lg" style="z-index: 555;">


        <div :class="{'bg-emerald-500' : type != 'info' && type != 'error' && type != 'warning', 'bg-lightBlue-500' : type == 'info', 'bg-red-500' : type == 'error', 'bg-amber-500' : type == 'warning'}" class="relative flex flex-col shadow rounded-md py-3 pl-6 pr-5 bg-emerald-500 w-auto" x-cloak x-show="open1" x-transition:enter="transition ease-out duration-500"
          x-transition:enter-start="opacity-0 transform -translate-y-2"
          x-transition:enter-end="opacity-100 transform translate-y-0"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
          x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2">
              <div class="flex flex-row items-center w-full">
                <div class="text-white">
                  <svg x-show="type != 'info' && type != 'error' && type != 'warning'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  <svg x-show="type == 'warning'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <svg x-show="type == 'info'" class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" xml:space="preserve"><style type="text/css">
                    .st0{fill:#fff;}
                    .st1{fill:#fff;stroke:#fff;stroke-width:32;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
                  </style><g id="Layer_1"/><g id="Layer_2"><g><path class="st0" d="M256,46.66C140.38,46.66,46.66,140.38,46.66,256S140.38,465.34,256,465.34S465.34,371.62,465.34,256    S371.62,46.66,256,46.66z M256,110.88c17.07,0,30.91,13.84,30.91,30.91c0,17.07-13.84,30.91-30.91,30.91s-30.91-13.84-30.91-30.91    C225.09,124.72,238.93,110.88,256,110.88z M287.03,370.09c0,17.14-13.89,31.03-31.03,31.03l0,0c-17.14,0-31.03-13.89-31.03-31.03    V257.61c0-17.14,13.89-31.03,31.03-31.03l0,0c17.14,0,31.03,13.89,31.03,31.03V370.09z"/></g></g></svg>

                  <svg x-show="type == 'error'" class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 512 512" height="512px" id="Layer_1" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><path d="M255.997,460.351c112.685,0,204.355-91.668,204.355-204.348S368.682,51.648,255.997,51.648  c-112.68,0-204.348,91.676-204.348,204.355S143.317,460.351,255.997,460.351z M255.997,83.888  c94.906,0,172.123,77.209,172.123,172.115c0,94.898-77.217,172.117-172.123,172.117c-94.9,0-172.108-77.219-172.108-172.117  C83.888,161.097,161.096,83.888,255.997,83.888z" style="fill: currentColor;"/><path d="M172.077,341.508c3.586,3.523,8.25,5.27,12.903,5.27c4.776,0,9.54-1.84,13.151-5.512l57.865-58.973l57.878,58.973  c3.609,3.672,8.375,5.512,13.146,5.512c4.658,0,9.316-1.746,12.902-5.27c7.264-7.125,7.369-18.793,0.242-26.051l-58.357-59.453  l58.357-59.461c7.127-7.258,7.021-18.92-0.242-26.047c-7.252-7.123-18.914-7.018-26.049,0.24l-57.878,58.971l-57.865-58.971  c-7.135-7.264-18.797-7.363-26.055-0.24c-7.258,7.127-7.369,18.789-0.236,26.047l58.351,59.461l-58.351,59.453  C164.708,322.715,164.819,334.383,172.077,341.508z" style="fill: currentColor;"/></svg>
                </div>
                <div class="text-sm font-medium ml-3 text-white" x-text="data"></div>
                <div @click="open1 = false; openNotify = false;" class="text-gray-300 hover:text-white cursor-pointer ml-5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
              </div>
            </div>
      </div>
@endif