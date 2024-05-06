<nav x-data="{ open: false }"
    class="bg-white shadow {{ request()->routeIs('home') ? 'bg-opacity-30 backdrop-blur-sm backdrop-filter' : '' }}">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a class="flex items-center justify-center" href="{{ route('home') }}">
                        <img class="block h-10 w-auto fill-current mr-3" src="{{ asset('img/logo.png') }}"
                            alt="Darul Huda Assam Campus">
                        <div class="font-bold text-2xl uppercase text-gray-700">DH Assam Campus</div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex ">
                    <!-- sm:flex -->

                    @php
                    $results = ($settings->results_starting_at ?? false) &&
                    \Carbon\Carbon::now()->between(($settings->results_starting_at ??
                    \Carbon\Carbon::today()->format('Y-m-d')), ($settings->results_ending_at ??
                    \Carbon\Carbon::today()->format('Y-m-d')));

                    $application_open = ($settings->starting_at ?? false) &&
                    \Carbon\Carbon::now()->between($settings->starting_at, $settings->ending_at);

                    $links = array_merge(['Home' => 'home', 'Exam Results' => 'marksheet', 'Dashboard' => 'dashboard'],
                    $application_open ? [
                    'Apply' => 'apply',
                    'Applications' => 'applications',
                    'Search' => 'applications'] : [], $results ? ['Results' => 'results'] : []);
                    if(!Auth::check() && isset($links['Dashboard']))
                    unset($links['Dashboard']);
                    else if (isset($links['Search']))
                    unset($links['Search']);
                    @endphp

                    @foreach($links as $name => $route)
                    <x-nav-link :href="route($route)" :active="request()->routeIs($route) && $name != 'Search'">
                        {{ $name }}
                    </x-nav-link>
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                @endauth
                @guest
                <a href="{{ route('apply') }}"
                    class="px-6 py-2 text-white bg-blue-600 shadow-md rounded-xl hover:bg-blue-700 block mx-2">
                    Apply Now
                </a>
                @endguest

            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach($links as $name => $route)
            <x-responsive-nav-link :href="route($route)" :active="request()->routeIs($route) && $name != 'Search'">
                {{ $name }}
            </x-responsive-nav-link>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @endauth

            @guest
            <x-responsive-nav-link class="bg-blue-100" :href="route('apply')">
                Apply Now
            </x-responsive-nav-link>
            @endguest
        </div>
    </div>
</nav>