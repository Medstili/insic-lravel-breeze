<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Insic Coach Profile</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-.min.css"/>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
   @vite(['resources/css/app.css', 'resources/js/app.js']) 

  <!-- Custom Styles -->
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #ecf0f1;;
      margin: 0;
      overflow-x: hidden;
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      min-height: calc(100vh - 80px);
      background-color: #ecf0f1;
    }    
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background-color: #2c3e50;
      color: #ecf0f1;
      display: flex;
      flex-direction: column;
      padding: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }
    .sidebar .logo {
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar form {
      margin-bottom: 10px;
    }
    .sidebar .menu-item {
      width: 100%;
      background: transparent;
      border: none;
      color: #ecf0f1;
      text-align: left;
      padding: 10px 15px;
      font-size: 1rem;
      border-radius: 4px;
      transition: background 0.3s;
    }
    .sidebar .menu-item:hover {
      background: rgba(236, 240, 241, 0.15);
      cursor: pointer;
    }

    /* Top Navbar */
    .navbar {
      margin-left: 250px;
      height: 80px;
      background-color: #fff;
      display: flex;
      align-items: left ;
      justify-content: flex-end;
      padding:  0px 40px 0px 0px ;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 900;
    }
    /* table style */
     .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table thead {
        background-color: #f4f4f4;
    }
    table th, table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
        font-size: 0.9rem;
    }
    table tbody tr:hover {
        background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">Insic</div>
    <form action="{{ route('appointments_list',auth()->user()->id) }}" method="get">
      <button class="menu-item">Appointments</button>
    </form>
    <form action="{{ route('patients_list',Auth::user()->id) }}" method="get">
      <button class="menu-item">Patients</button>
    </form>
  </div>
  <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 navbar">
      <!-- Primary Navigation Menu  -->
      <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
              <!-- Settings Dropdown -->
              <div class="hidden sm:flex sm:items-center sm:ms-6">
                  <x-dropdown align="right" width="48">
                      <x-slot name="trigger">
                          <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                              <div>{{ Auth::user()->full_name }}</div>

                              <div class="ms-1">
                                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                  </svg>
                              </div>
                          </button>
                      </x-slot>

                      <x-slot name="content">
                          <x-dropdown-link :href="route('coach_profile',Auth::user()->id)">
                              {{ __('Profile') }}
                          </x-dropdown-link> 

                          <!-- Authentication -->
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf

                              <x-dropdown-link :href="route('logout')"
                                      onclick="event.preventDefault();
                                                  this.closest('form').submit();">
                                  {{ __('Log Out') }}
                              </x-dropdown-link>
                          </form>
                      </x-slot>
                  </x-dropdown>
              </div>
              <!-- Hamburger -->
              <div class="-me-2 flex items-center sm:hidden">
                  <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                      <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                          <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                          <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>
          </div>
      </div>
      <!-- Responsive Navigation Menu -->
      <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
          <!-- <div class="pt-2 pb-3 space-y-1">
              <x-responsive-nav-link :href="route('global_dashboard')" :active="request()->routeIs('global_dashboard')">
                  {{ __('Dashboard') }}
              </x-responsive-nav-link>
          </div> -->

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
              <div class="px-4">
                  <div class="font-medium text-base text-gray-800">{{ Auth::user()->full_name }}</div>
                  <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
              </div>

              <div class="mt-3 space-y-1">
                  <x-responsive-nav-link 
                  :href="route('user.show',Auth::user()->id)"
                  :active="request()->routeIs('coach_profile',Auth::user()->id)" > 
                      {{ __('Profile') }}
                  </x-responsive-nav-link>

                  <!-- Authentication -->
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf

                      <x-responsive-nav-link :href="route('logout')"
                              onclick="event.preventDefault();
                                          this.closest('form').submit();">
                          {{ __('Log Out') }}
                      </x-responsive-nav-link>
                  </form>
              </div>
          </div>
      </div>
  </nav>
  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
