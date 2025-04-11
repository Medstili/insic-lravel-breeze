<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <title>Insic Dashboard</title>
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- SortableJS -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

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

    /* Sidebar */
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

    /* Navbar */
    .custom-navbar {
      background: white;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding: 1rem;
      height: 80px;
      width: calc(100% - 280px);
      position: fixed;
      top: 0;
      left: 280px;
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .custom-navbar .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
    }

    /* Main Content */
    .main-content {
      width: calc(100% - 280px);
      margin-left: 280px;
      padding: 2rem;
      position: relative;
      top: 60px;
      height: calc(100vh - 80px);
      overflow-y: auto;
      transition: all 0.3s ease;
    }

    .card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    /* Notifications */
    .notification-dropdown {
      position: absolute;
      top: 40px;
      right: 0;
      width: 400px;
      max-height: 500px;
      overflow-y: auto;
      background: white;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1050;
    }

    .notification-item {
      padding: 10px;
      margin: 10px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .notification-item.unread {
      background: rgba(124, 126, 227, 0.77);
      color: white;
    }

    .notification-count {
      position: absolute;
      top: -5px;
      right: -5px;
      font-size: 12px;
      padding: 3px 6px;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
      background: var(--primary-color);
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
      .sidebar {
        margin-left: -280px;
        z-index: 1000;
      }
      
      .sidebar.active {
        margin-left: 0;
      }

      .custom-navbar {
        width: 100%;
        left: 0;
        padding: 1rem;
      }

      .main-content {
        width: 100%;
        margin-left: 0;
        padding: 1rem;
        top: 80px;
      }

      .mobile-menu-btn {
        display: block;
      }
    }

    @media (max-width: 768px) {
      .notification-dropdown {
        width: 100vw;
        right: -20px;
      }

      .custom-navbar .profile-img {
        width: 35px;
        height: 35px;
      }

      .menu-item {
        padding: 0.75rem 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .logo {
        font-size: 1.5rem;
        padding: 1rem;
      }

      .notification-item p {
        font-size: 10px;
      }

      .notification-item small {
        font-size: 8px;
      }

      .main-content {
        padding: 1rem 0.5rem;
      }
    }
  </style>
</head>
<body class="overflow-x-hidden" x-data="{ sidebarOpen: false }">
  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" @click="sidebarOpen = !sidebarOpen">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar fixed h-full" :class="{ 'active': sidebarOpen }">
    <div class="logo">Insic</div>
    <div class="mt-4">
      <form action="{{ route('global_dashboard') }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-chart-pie"></i>
          <span>Tableau de bord</span>
        </button>
      </form>
      <form action="{{ route('appointment.index') }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-calendar-check"></i>
          <span>Rendez-vous</span>
        </button>
      </form>
      <form action="{{ route('suggested-appointments') }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-lightbulb"></i>
          <span>Suggérés</span>
        </button>
      </form>
      <form action="{{ route('user.index') }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-users"></i>
          <span>Coachs</span>
        </button>
      </form>
      <form action="{{ route('patient.index') }}" method="get">
        <button class="menu-item w-full text-left">
          <i class="fas fa-user-injured"></i>
          <span>Patients</span>
        </button>
      </form>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="custom-navbar">
    <div class="d-flex justify-content-end align-items-center">
      <div class="d-flex align-items-center gap-4" style="width: 15%;">
        <div id="notificationContainer" class="position-relative">
          <div id="notificationIcon" onclick="toggleNotifications()" class="position-relative">
            <i class="fa-solid fa-bell" style="font-size: 1.5rem;"></i>
            <span id="notificationCount" class="notification-count badge bg-danger rounded-circle {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'd-none' }}">
              {{ auth()->user()->unreadNotifications->count() }}
            </span>
          </div>
          <div id="notificationDropdown" class="notification-dropdown d-none">
            @if(auth()->user()->notifications->isEmpty())
              <p class="text-center p-3">There are no notifications</p>
            @else
              @foreach(auth()->user()->notifications as $notification)
                <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" data-id="{{ $notification->id }}">
                  <p>{{ $notification->data['message'] ?? 'No message provided' }}</p>
                  <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
              @endforeach
            @endif
          </div>
        </div>
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="d-flex align-items-center px-3 py-2 border-0 bg-transparent">
              <div>{{ Auth::user()->full_name }}</div>
              <div class="ms-1">
                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
              </div>
            </button>
          </x-slot>
          <x-slot name="content">
            <x-dropdown-link :href="route('user.show',Auth::user()->id)">
              <i class="fas fa-user text-sm me-2"></i>
              {{ __('Profil') }}
            </x-dropdown-link>
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
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content pt-24">
    @yield('content')
  </div>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    // Menu active state
    const currentPath = window.location.pathname;
    document.querySelectorAll('.menu-item').forEach(item => {
      if (item.closest('form').action.includes(currentPath)) {
        item.classList.add('active');
        item.style.background = 'rgba(255, 255, 255, 0.15)';
        item.style.transform = 'translateX(8px)';
      }
    });

    // Notifications
    function toggleNotifications() {
      const dropdown = document.getElementById('notificationDropdown');
      dropdown.classList.toggle('d-none');
    }

    document.addEventListener('click', function(event) {
      const notificationContainer = document.getElementById('notificationContainer');
      const dropdown = document.getElementById('notificationDropdown');
      if (!notificationContainer.contains(event.target)) {
        dropdown.classList.add('d-none');
      }
    });

    document.querySelectorAll('.notification-item').forEach(item => {
      item.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        fetch('{{ route("notifications.mark-as-read") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.classList.remove('unread');
            const countElement = document.getElementById('notificationCount');
            let count = parseInt(countElement.textContent);
            if (count > 0) count--;
            countElement.textContent = count;
            if (count === 0) countElement.classList.add('d-none');
          }
        });
      });
    });
  </script>
</body>
</html>