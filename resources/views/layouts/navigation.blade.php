<ul class="px-3 py-5 space-y-2 text-sm font-medium">
  <li>
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-md transition-all 
       hover:bg-gray-100 dark:hover:bg-white/10 
       {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-white/10 text-indigo-500 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 0h8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <span>Dashboard</span>
    </a>
  </li>

  <li>
    <a href="{{ route('stats.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-md transition-all 
       hover:bg-gray-100 dark:hover:bg-white/10 
       {{ request()->routeIs('stats.index') ? 'bg-gray-100 dark:bg-white/10 text-indigo-500 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <span>Statistik</span>
    </a>
  </li>

  <li>
    <a href="{{ route('surveys.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-md transition-all 
       hover:bg-gray-100 dark:hover:bg-white/10 
       {{ request()->routeIs(['surveys.index', 'data_sensus.create', 'data_sensus.edit']) ? 'bg-gray-100 dark:bg-white/10 text-indigo-500 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 10h11M9 21V3M17 21V8m4 13V12" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <span>Data Sensus</span>
    </a>
  </li>

  <li>
    <a href="{{ route('surveys.voicecreate') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-md transition-all 
       hover:bg-gray-100 dark:hover:bg-white/10 
       {{ request()->routeIs('surveys.voicecreate') ? 'bg-gray-100 dark:bg-white/10 text-indigo-500 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zM6 20v-2a4 4 0 014-4h0a4 4 0 014 4v2"
              stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <span>Voice</span>
    </a>
  </li>
  <li>
    <a href="{{ route('profile.edit') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-md transition-all 
       hover:bg-gray-100 dark:hover:bg-white/10 
       {{ request()->routeIs('profile.edit') ? 'bg-gray-100 dark:bg-white/10 text-indigo-500 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zM6 20v-2a4 4 0 014-4h0a4 4 0 014 4v2"
              stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <span>Profil</span>
    </a>
  </li>
  <li class="pt-4 border-t border-gray-200 dark:border-gray-700">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition-all text-gray-700 dark:text-gray-300">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span>Keluar</span>
      </button>
    </form>
  </li>
</ul>
