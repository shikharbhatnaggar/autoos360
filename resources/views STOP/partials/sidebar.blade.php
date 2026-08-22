<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col">

    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-slate-800">

        <div class="h-10 w-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">
            M
        </div>

        <div class="ml-3">
            <h1 class="text-white font-semibold">
                AutoOS360
            </h1>

            <p class="text-xs text-slate-400">
                Vehicle Inventory
            </p>
        </div>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-6">

        <ul class="space-y-1">

            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-6 py-3 transition rounded-lg mx-3
                   {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow' : 'hover:bg-slate-800' }}">

                    <x-heroicon-o-home class="w-5 h-5" />

                    <span>Dashboard</span>

                </a>
            </li>

            <li>
                <a href="{{ route('vehicles.index') }}"
                   class="flex items-center gap-3 px-6 py-3 transition rounded-lg mx-3
                   {{ request()->routeIs('vehicles.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-slate-800' }}">

                    <x-heroicon-o-truck class="w-5 h-5" />

                    <span>Vehicles</span>

                </a>
            </li>

            <!-- <li>
                <a href="{{ route('reports.profit_loss') }}"
                   class="flex items-center gap-3 px-6 py-3 transition rounded-lg mx-3
                   {{ request()->routeIs('purchases.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-slate-800' }}">

                    🛒

                    <span>Purchases</span>

                </a>
            </li> -->

            <!-- <li>
                <a href="{{ route('reports.profit_loss') }}"
                   class="flex items-center gap-3 px-6 py-3 transition rounded-lg mx-3
                   {{ request()->routeIs('sales.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-slate-800' }}">

                    💰

                    <span>Sales</span>

                </a>
            </li> -->

            <!-- <li>
                <a href="{{ route('reports.profit_loss') }}"
                   class="flex items-center gap-3 px-6 py-3 transition rounded-lg mx-3
                   {{ request()->routeIs('expenses.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-slate-800' }}">

                    💳

                    <span>Expenses</span>

                </a>
            </li> -->

        </ul>

        <div class="border-t border-slate-800 my-6"></div>

        <p class="px-6 text-xs uppercase tracking-wider text-slate-500 mb-3">
            Administration
        </p>

        <ul class="space-y-1">

            @if(auth()->user()->hasRole('admin','branch_manager'))

                <li>
                    <a href="{{ route('brokers.index') }}"
                       class="flex items-center gap-3 px-6 py-3 rounded-lg mx-3
                       {{ request()->routeIs('brokers.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

                        <x-heroicon-o-users class="w-5 h-5" />

                        <span>Brokers</span>

                    </a>
                </li>

            @endif

            @if(auth()->user()->isAdmin())

                <li>
                    <a href="{{ route('branches.index') }}"
                       class="flex items-center gap-3 px-6 py-3 rounded-lg mx-3
                       {{ request()->routeIs('branches.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

                        <x-heroicon-o-building-office-2 class="w-5 h-5" />

                        <span>Branches</span>

                    </a>
                </li>

            @endif

            <li>

                <a href="{{ route('reports.stock') }}"
                   class="flex items-center gap-3 px-6 py-3 rounded-lg mx-3
                   {{ request()->routeIs('reports.stock') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

                    <x-heroicon-o-archive-box class="w-5 h-5" />

                    <span>Stock</span>

                </a>

            </li>

            <li>

                <a href="{{ route('reports.profit_loss') }}"
                   class="flex items-center gap-3 px-6 py-3 rounded-lg mx-3
                   {{ request()->routeIs('reports.profit_loss') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

                    <x-heroicon-o-chart-bar class="w-5 h-5" />

                    <span>Profit & Loss</span>

                </a>

            </li>

        </ul>

    </nav>

    <!-- User -->
    <!-- <div class="border-t border-slate-800 p-5">

        <div class="text-white font-medium">
            {{ auth()->user()->name }}
        </div>

        <div class="text-sm text-slate-400">
            {{ auth()->user()->role->label }}
        </div>

    </div> -->

    <div
        x-data="{ open:false }"
        class="relative border-t border-slate-800 p-4">

        <button
            @click="open=!open"
            class="flex w-full items-center justify-between rounded-lg p-2 hover:bg-slate-800 transition">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">

                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}

                </div>

                <div class="text-left">

                    <div class="font-medium text-white">

                        {{ auth()->user()->name }}

                    </div>

                    <div class="text-xs text-slate-400">

                        {{ auth()->user()->role->label }}

                    </div>

                </div>

            </div>

            <x-heroicon-o-chevron-up
                class="h-5 w-5 text-slate-400 transition"
                ::class="{ 'rotate-180': open }"/>

        </button>

        <!-- Popup -->

        <div
            x-show="open"
            x-cloak
            @click.outside="open=false"
            x-transition
            class="absolute bottom-20 left-4 right-4 rounded-xl border border-slate-700 bg-slate-900 shadow-2xl">

            <!-- <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800">

                <x-heroicon-o-user class="w-5 h-5"/>

                Profile

            </a> -->

            <a
                href="{{ route('account.password.edit') }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800">

                <x-heroicon-o-key class="w-5 h-5"/>

                Change Password

            </a>

            <div class="border-t border-slate-700"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 px-4 py-3 hover:bg-slate-800">

                    <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />

                    Logout

                </button>
            </form>

        </div>

    </div>

</aside>