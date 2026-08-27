<header class="h-16 shrink-0 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6">

    <!-- Left -->
    <div class="flex min-w-0 items-center gap-3 sm:gap-6">

        <!-- Mobile menu button -->
        <button
            type="button"
            @click="sidebarOpen = true"
            class="lg:hidden text-gray-500 hover:text-gray-700"
            aria-label="Open sidebar">

            <x-heroicon-o-bars-3 class="w-6 h-6"/>

        </button>

        <div class="min-w-0">

            <h1 class="truncate text-lg font-semibold text-gray-800 sm:text-xl">

                @yield('page-title','Dashboard')

            </h1>

            <!-- <p class="text-sm text-gray-500">

                Welcome back, {{ auth()->user()->name }}

            </p> -->

        </div>

    </div>

    <!-- Right -->
    <div class="flex shrink-0 items-center gap-2 sm:gap-4">

        <!-- Search -->
        <!-- <div class="hidden md:flex items-center">

            <div class="relative">

                <x-heroicon-o-magnifying-glass
                    class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"/>

                <input
                    type="text"
                    placeholder="Search..."
                    class="pl-10 pr-4 py-2 w-64 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            </div>

        </div> -->

        <!-- Dark Mode -->
        <!-- <button
            class="p-2 rounded-lg hover:bg-gray-100">

            <x-heroicon-o-moon class="w-6 h-6 text-gray-600"/>

        </button> -->

        <!-- Notifications -->
        <!-- <button
            class="relative p-2 rounded-lg hover:bg-gray-100">

            <x-heroicon-o-bell class="w-6 h-6 text-gray-600"/>

            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500"></span>

        </button> -->

        <div x-data="{ open: false }" class="relative">

            <button
                type="button"
                @click="open = !open"
                @click.outside="open = false"
                class="relative p-2 rounded-lg hover:bg-gray-100">

                <x-heroicon-o-bell class="w-6 h-6 text-gray-600"/>
                
                @if($newLeadsNotificationCount > 0)
                    <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                @endif

            </button>

            <div
                x-show="open"
                x-cloak
                x-transition
                class="absolute right-0 mt-2 w-80 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800 z-50">

                <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        New Leads Received
                    </h3>
                    @if($newLeadsNotificationCount > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $newLeadsNotificationCount }} New
                        </span>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($newLeadsNotificationList as $notificationLead)
                        <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notificationLead->name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    Interested in vehicle #{{ $notificationLead->vehicle_id }}
                                </span>
                                <span class="text-[10px] text-blue-600 font-medium mt-1">
                                    {{ $notificationLead->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center">
                            <x-heroicon-o-bell-slash class="mx-auto h-8 w-8 text-gray-400"/>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                No new notifications
                            </p>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
        

        <!-- User -->
        <div class="flex items-center gap-3 border-l pl-4">

            <div class="text-right hidden md:block">

                <div class="font-medium text-gray-800">

                     {{ tenant()->name }}

                </div>

                <div class="text-xs text-gray-500">

                     {{ auth()->user()->branch?->name ?? 'Head Office' }}

                </div>

            </div>

            <div
                class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">

                {{ strtoupper(substr(tenant()->name,0,1)) }}

            </div>

        </div>

    </div>

</header>