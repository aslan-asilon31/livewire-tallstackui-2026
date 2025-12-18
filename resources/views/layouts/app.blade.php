<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />




  <tallstackui:script />
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-cloak x-data="{ name: @js(auth()->user()->name) }" x-on:name-updated.window="name = $event.detail.name"
  x-bind:class="{ 'dark bg-gray-800': darkTheme, 'bg-gray-100': !darkTheme }">
  <x-layout>
    <x-slot:top>
      <x-dialog />
      <x-toast />
    </x-slot:top>
    <x-slot:header>
      <x-layout.header>
        <x-slot:left>
          <x-theme-switch />
        </x-slot:left>
        <x-slot:right>
          <x-dropdown>
            <x-slot:action>
              <div>
                <button class="cursor-pointer" x-on:click="show = !show">
                  <span class="text-base font-semibold text-primary-500" x-text="name"></span>
                </button>
              </div>
            </x-slot:action>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-dropdown.items :text="__('Profile')" :href="route('user.profile')" />
              <x-dropdown.items :text="__('Logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                separator />
            </form>
          </x-dropdown>
        </x-slot:right>
      </x-layout.header>
    </x-slot:header>
    <x-slot:menu>
      <x-side-bar smart collapsible>
        <x-slot:brand>
          <div class="mt-8 flex items-center justify-center">
            {{-- <img src="{{ asset('/assets/images/tsui.png') }}" width="40" height="40" /> --}}
            <b>アスラン</b>
          </div>
        </x-slot:brand>
        <x-side-bar.item text="Dashboard" icon="home" :route="route('dashboard')" />
        <x-side-bar.item text="Reports" icon="document-text" :route="route('reports.index')" />
        <x-side-bar.item text="Employee" icon="user-group" :route="route('users.index')" />
        <x-side-bar.item text="Sales" icon="shopping-bag" :route="route('sales.index')" />
        <x-side-bar.item text="Inventory" icon="cube" :route="route('inventory.index')" />
        <x-side-bar.item text="Customer" icon="users" :route="route('customer.index')" />
        <x-side-bar.item text="Procurement" icon="shopping-cart" :route="route('procurement.index')" />
        <x-side-bar.item text="Finance" icon="currency-dollar" :route="route('finance.index')" />

        <x-side-bar.item text="Welcome Page" icon="arrow-uturn-left" :route="route('welcome')" />
      </x-side-bar>
    </x-slot:menu>
    {{ $slot }}
  </x-layout>
  @livewireScripts
</body>

</html>
