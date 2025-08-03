<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <title>Insic - Healthcare Management Platform</title>
  
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>

  <!-- SortableJS -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

</head>
<body class="overflow-x-hidden" x-data="{ sidebarOpen: false, notificationsOpen: false }">
  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" @click="sidebarOpen = !sidebarOpen">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar bg-cyan-500  text-white" :class="{ 'active': sidebarOpen }">
    <div class="logo w-full text-white mt-6 mb-6 flex items-center justify-center">Insic</div>
    <nav class="mt-6">
      <form action="{{ route('global_dashboard') }}" method="get">
        <button class="menu-item w-full text-left hover:bg-cyan-600 hover:text-white" type="submit">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </button>
      </form>
      <form action="{{ route('appointment.index') }}" method="get">
        <button class="menu-item w-full text-left hover:bg-cyan-600 hover:text-white">
          <i class="fas fa-calendar-check"></i>
          <span>Appointments</span>
        </button>
      </form>
      <form action="{{ route('suggested-appointments') }}" method="get">
        <button class="menu-item w-full text-left hover:bg-cyan-600 hover:text-white">
          <i class="fas fa-lightbulb"></i>
          <span>Suggestions</span>
        </button>
      </form>
      <form action="{{ route('user.index') }}" method="get">
        <button class="menu-item w-full text-left hover:bg-cyan-600 hover:text-white">
          <i class="fas fa-user-md"></i>
          <span>Coaches</span>
        </button>
      </form>
      <form action="{{ route('patient.index') }}" method="get">
        <button class="menu-item w-full text-left hover:bg-cyan-600 hover:text-white">
          <i class="fas fa-users"></i>
          <span>Patients</span>
        </button>
      </form>
    </nav>
  </div>

  <!-- Navbar -->
  <nav class="navbar bg-cyan-500 text-white shadow-md">
    <div class="navbar-brand text-white font-bold">Insic</div>
    
    <div class="navbar-actions">
      <!-- Notifications -->
      <div class="relative">
        <button class="notification-btn text-white hover:bg-cyan-700" @click="notificationsOpen = !notificationsOpen">
          <i class="fas fa-bell text-lg"></i>
          @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
          @endif
        </button>
        
        <div class="notification-dropdown" x-show="notificationsOpen" x-cloak>
          <div class="p-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-900">Notifications</h3>
          </div>
            @if(auth()->user()->notifications->isEmpty())
            <div class="p-4 text-center text-gray-500">
              <i class="fas fa-bell-slash text-2xl mb-2"></i>
              <p>No notifications</p>
            </div>
            @else
            @foreach(auth()->user()->notifications->take(5) as $notification)
              <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                   data-id="{{ $notification->id }}" onclick="markAsRead({{ $notification->id }})">
                <p class="text-sm text-gray-900 mb-1">{{ $notification->data['message'] ?? 'No message provided' }}</p>
                <small class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
              @endforeach
            @endif
          </div>
        </div>

      <!-- User Menu -->
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
          <div class="user-menu bg-cyan-100 text-cyan-900 hover:bg-cyan-200">
            <div class="user-avatar bg-gradient-to-br from-cyan-500 to-sky-500">
              {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
            </div>
            <div class="user-info">
              <div class="user-name">{{ Auth::user()->full_name }}</div>
              <div class="user-role">Administrator</div>
            </div>
            <i class="fas fa-chevron-down text-gray-400"></i>
              </div>
          </x-slot>
        
          <x-slot name="content">
          <x-dropdown-link :href="route('user.show',Auth::user()->id)" class="flex items-center">
            <i class="fas fa-user text-sm mr-2"></i>
            {{ __('Profile') }}
            </x-dropdown-link>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center">
              <i class="fas fa-sign-out-alt text-sm mr-2"></i>
              {{ __('Logout') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content">
    <div class="content-header">
      @yield('header')
    </div>
    
    <div class="fade-in">
    @yield('content')
  </div>
  </main>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    // Menu active state
    const currentPath = window.location.pathname;
    document.querySelectorAll('.menu-item').forEach(item => {
      if (item.closest('form').action.includes(currentPath)) {
        item.classList.add('active');
      }
    });

    // Mark notification as read
    function markAsRead(notificationId) {
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
          const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
          notificationItem.classList.remove('unread');
          
          const badge = document.querySelector('.notification-badge');
          if (badge) {
            let count = parseInt(badge.textContent);
            if (count > 0) count--;
            badge.textContent = count;
            if (count === 0) badge.style.display = 'none';
          }
        }
      });
    }

    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
      const notificationBtn = document.querySelector('.notification-btn');
      const notificationDropdown = document.querySelector('.notification-dropdown');
      
      if (!notificationBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
        Alpine.store('notificationsOpen', false);
      }
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
</body>
</html>