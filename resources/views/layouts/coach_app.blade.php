<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Insic Coach Profil</title>

  <!-- Polices & Icônes -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
   <!-- Scripts  -->
   @vite(['resources/css/app.css', 'resources/js/app.js']) 

   <!--  bootstrap Scripts and links-->

     <!-- liens  -->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>

   <!-- scripts -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- Styles personnalisés -->
  <style>
    :root {
      --primary-color: #6366f1;
      --secondary-color: #4f46e5;
      --accent-color: #818cf8;
      --light-bg: #f8fafc;
      --dark-text: #1e293b;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: var(--dark-text);
    }

    /* Contenu principal */
    .main-content {
      width: calc(100% - 280px);
      margin-left: 280px;
      padding: 2rem;
      position: relative;
      top: 60px; /* Pousser le contenu en dessous de la barre de navigation fixe */
      height: calc(100vh - 80px); /* Hauteur totale moins la barre de navigation */
      overflow-y: auto; /* Ajouter un défilement si le contenu déborde */
}
 
  /* Barre latérale */
    .sidebar {
      width: 280px;
      background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .sidebar .logo {
      font-size: 1.75rem;
      font-weight: 700;
      color: white;
      padding: 1.5rem;
      text-align: center;
      letter-spacing: 1px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: 1rem 2rem;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
    }

    .menu-item i {
      width: 30px;
      font-size: 1.1rem;
    }

    .menu-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(8px);
    }
      /* Barre de navigation */
    .custom-navbar {
      background: white;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding:  3rem;
      height: 80px;
      width: calc(100% - 280px);
      position: fixed;
      top: 0;
      left: 280px;
      z-index: 1000;
    }
    /* Style de table */
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

  <!-- Barre latérale -->

  <div class="sidebar fixed h-full">
    <div class="logo">Insic</div>
    <div class="mt-4">
      <form action="{{ route('appointments_list',auth()->user()->id) }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-calendar-check"></i>
          <span>Rendez-vous</span>
        </button>
      </form>
      <form action="{{ route('patients_list',Auth::user()->id) }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-lightbulb"></i>
          <span>Patients</span>
        </button>
      </form>
    </div>
  </div>


  <nav x-data="{ open: false }" class="custom-navbar z-50">
            <div class="d-flex justify-content-end align-items-center space-x-4">
                <x-dropdown align="right" width="48" class="cursor-pointer">
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
                    <i class="fas fa-user text-sm me-2"></i>
                      {{ __('Profil') }}
                    </x-dropdown-link> 

                    <!-- Authentification -->
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf

                      <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                      <i class="fas fa-sign-out-alt text-sm me-2"></i>
                        {{ __('Déconnexion') }}
                      </x-dropdown-link>
                    </form>

                  </x-slot>
                </x-dropdown>
            </div>
  </nav>

  
  <!-- Contenu principal -->
  <div class="main-content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
