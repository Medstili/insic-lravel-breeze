<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <title>Insic - Coach Dashboard</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.js"></script>

  <!-- <style>
    :root {
      /* Modern Color Palette */
      --primary-50: #eff6ff;
      --primary-100: #dbeafe;
      --primary-200: #bfdbfe;
      --primary-300: #93c5fd;
      --primary-400: #60a5fa;
      --primary-500: #3b82f6;
      --primary-600: #2563eb;
      --primary-700: #1d4ed8;
      --primary-800: #1e40af;
      --primary-900: #1e3a8a;
      
      /* Neutral Colors */
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-600: #4b5563;
      --gray-700: #374151;
      --gray-800: #1f2937;
      --gray-900: #111827;
      
      /* Semantic Colors */
      --success-50: #f0fdf4;
      --success-500: #22c55e;
      --success-600: #16a34a;
      --warning-50: #fffbeb;
      --warning-500: #f59e0b;
      --warning-600: #d97706;
      --error-50: #fef2f2;
      --error-500: #ef4444;
      --error-600: #dc2626;
      
      /* Shadows */
      --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
      
      /* Border Radius */
      --radius-sm: 0.375rem;
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --radius-xl: 1rem;
      --radius-2xl: 1.5rem;
      
      /* Spacing */
      --space-1: 0.25rem;
      --space-2: 0.5rem;
      --space-3: 0.75rem;
      --space-4: 1rem;
      --space-5: 1.25rem;
      --space-6: 1.5rem;
      --space-8: 2rem;
      --space-10: 2.5rem;
      --space-12: 3rem;
      --space-16: 4rem;
      --space-20: 5rem;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: var(--gray-50);
      color: var(--gray-900);
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
    /* .sidebar {
      width: 280px;
      background: linear-gradient(135deg, var(--success-600) 0%, var(--success-700) 100%);
      box-shadow: var(--shadow-xl);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: fixed;
      height: 100vh;
      z-index: 50;
      overflow-y: auto;
    }

    .sidebar .logo {
      font-size: 1.875rem;
      font-weight: 800;
      color: white;
      padding: var(--space-6);
      text-align: center;
      letter-spacing: -0.025em;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.05);
    }

    .sidebar .logo::before {
      content: "👨‍⚕️";
      margin-right: var(--space-2);
      font-size: 1.5rem;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: var(--space-4) var(--space-6);
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      font-weight: 500;
      font-size: 0.875rem;
      border-radius: var(--radius-md);
      margin: var(--space-1) var(--space-4);
    }

    .menu-item i {
      width: 20px;
      font-size: 1rem;
      margin-right: var(--space-3);
      opacity: 0.9;
    }

    .menu-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(4px);
    }

    .menu-item.active {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      box-shadow: var(--shadow-md);
    }

    .menu-item.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 20px;
      background: white;
      border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    } */

    /* Navbar */
    /* .navbar {
      background: white;
      box-shadow: var(--shadow-sm);
      padding: var(--space-4) var(--space-6);
      height: 80px;
      width: calc(100% - 280px);
      position: fixed;
      top: 0;
      left: 280px;
      z-index: 40;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .navbar-brand {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--gray-900);
    }

    .navbar-actions {
      display: flex;
      align-items: center;
      gap: var(--space-4);
    }

    .notification-btn {
      position: relative;
      background: none;
      border: none;
      padding: var(--space-2);
      border-radius: var(--radius-md);
      color: var(--gray-600);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .notification-btn:hover {
      background: var(--gray-100);
      color: var(--gray-900);
    }

    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: var(--error-500);
      color: white;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 2px 6px;
      border-radius: 9999px;
      min-width: 18px;
      text-align: center;
    } */

    .user-menu {
        display: flex;
        align-items: center;
      gap: var(--space-3);
      padding: var(--space-2) var(--space-3);
      border-radius: var(--radius-lg);
      background: var(--gray-50);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .user-menu:hover {
      background: var(--gray-100);
    }
    
    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--success-500), var(--success-600));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 0.875rem;
    }

    .user-info {
        display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--gray-900);
    }
    
    .user-role {
      font-size: 0.75rem;
      color: var(--gray-500);
    }
    
    /* Main Content */
    .main-content {
      /* width: calc(100% - 280px); */
      /* margin-left: 280px; */
      padding: var(--space-8);
      min-height: 100vh;
      background: var(--gray-50);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-header {
      margin-bottom: var(--space-8);
    }

    .page-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: var(--space-2);
      letter-spacing: -0.025em;
    }

    .page-subtitle {
      color: var(--gray-600);
      font-size: 1rem;
    }
    
    /* Mobile Menu Button */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      top: var(--space-4);
      left: var(--space-4);
      z-index: 60;
      background: var(--success-600);
      color: white;
      border: none;
      padding: var(--space-3);
      border-radius: var(--radius-md);
      cursor: pointer;
      box-shadow: var(--shadow-md);
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
    }
    
      .sidebar.active {
        transform: translateX(0);
      }

      .navbar {
        width: 100%;
        left: 0;
    }
    
    .main-content {
        width: 100%;
        margin-left: 0;
        padding: var(--space-4);
      }

      .mobile-menu-btn {
        display: block;
    }
    }

        @media (max-width: 768px) {
          .navbar {
            padding: var(--space-3) var(--space-4);
          }

          .user-info {
            display: none;
        }
        
          .page-title {
            font-size: 1.5rem;
        }
    }

        @media (max-width: 480px) {
        .sidebar {
            width: 100%;
        }
        
        .main-content {
            padding: var(--space-3);
        }
    }
  </style> -->
</head>
<body class="overflow-x-hidden" x-data="{ sidebarOpen: false, notificationsOpen: false }">
  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" @click="sidebarOpen = !sidebarOpen">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Navbar -->

  <nav class="coach_navbar  bg-cyan-500 text-white shadow-md">
    <div class="navbar-brand text-white font-bold">Insic</div>
    
    <div class="navbar-actions">
      <div class="relative">
      <!-- User Menu -->
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
          <div class="user-menu bg-cyan-100 text-cyan-900 hover:bg-cyan-200">
            <div class="user-avatar bg-gradient-to-br from-cyan-500 to-sky-500">
              {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
            </div>
            <div class="user-info">
              <div class="user-name">{{ Auth::user()->full_name }}</div>
              <div class="user-role">Entraîneur</div>
            </div>
            <i class="fas fa-chevron-down text-gray-400"></i>
              </div>
          </x-slot>
        
          <x-slot name="content">
          <x-dropdown-link :href="route('coach_profile',Auth::user()->id)" class="flex items-center">
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
  <main class="coach_main-content">
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
      if (item.href && item.href.includes(currentPath)) {
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
