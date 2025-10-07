{{-- resources/views/layouts/navigation.blade.php --}}

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
  <!-- Primary Navigation Menu -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo dan Link Dashboard -->
      <div class="flex">
        <div class="shrink-0 flex items-center">
          <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
          </a>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
          <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>
        </div>
      </div>

      <!-- Settings Dropdown (hanya untuk user yang sudah login) -->
      @auth
      <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition ease-in-out duration-150">
              <div>{{ Auth::user()->name }}</div>
              <div class="ms-1">
                <svg class="fill-current h-4 w-4" …>…</svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
              {{ __('Profile') }}
            </x-dropdown-link>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-dropdown-link :href="route('logout')"
                  onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
      @else
      <!-- Jika belum login, tampilkan link Login/Register -->
      <div class="hidden sm:flex sm:items-center sm:space-x-4">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Log in</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Register</a>
        @endif
      </div>
      @endauth

      <!-- Hamburger untuk mobile -->
      <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open" …>…</button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    @auth
      <!-- Link Dashboard di mobile -->
      <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
          {{ __('Dashboard') }}
        </x-responsive-nav-link>
      </div>
      <!-- User info & logout di mobile -->
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
          <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
          <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>
        <div class="mt-3 space-y-1">
          <x-responsive-nav-link :href="route('profile.edit')">
            {{ __('Profile') }}
          </x-responsive-nav-link>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
              {{ __('Log Out') }}
            </x-responsive-nav-link>
          </form>
        </div>
      </div>
    @else
      <!-- Link Login/Register di mobile -->
      <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
          {{ __('Log in') }}
        </x-responsive-nav-link>
        @if (Route::has('register'))
          <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
            {{ __('Register') }}
          </x-responsive-nav-link>
        @endif
      </div>
    @endauth
  </div>
</nav>
