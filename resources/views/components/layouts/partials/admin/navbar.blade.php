<div class="navbar bg-base-200 shadow-sm">
    <div class="flex-none lg:hidden">
        <label for="drawer-toggle" class="btn btn-square btn-ghost">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </label>
    </div>
    <div class="flex-1">
        <h1 class="text-xl font-bold">لوحة التحكم</h1>
    </div>
    <div class="flex-none gap-2">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full bg-primary text-primary-content !flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                {{-- <li><a>Profile</a></li>
                <li><a>Settings</a></li> --}}
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">تسجيل الخروج</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
