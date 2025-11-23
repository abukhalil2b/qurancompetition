<header class="z-40 w-full" :class="{ 'dark': $store.app.semidark }">
    <div class="shadow-sm w-full">

        <div class="w-full relative bg-white flex items-center px-5 py-2.5 dark:bg-[#0e1726]">

            <div class="w-full flex items-center justify-between dark:text-[#d0d2d6]">

                <!-- search -->
                <div class="sm:rtl:ml-auto">
                    <a href="/" class="main-logo flex items-center shrink-0">
                        <img class="w-8 ltr:-ml-1 rtl:-mr-1 inline" src="/assets/images/logo.png" alt="image" />
                        <span class="text-md ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle hidden md:inline dark:text-white-light transition-all duration-300">
                           mis
                        </span>
                    </a>
                </div>

                
                <!-- profile photo -->
                <div class="flex items-center gap-1">
                    <div>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'light'" href="javascript:;" class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60" @click="$store.app.toggleTheme('dark')">
                            <x-svgicon.sun />
                        </a>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'dark'" href="javascript:;" class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60" @click="$store.app.toggleTheme('system')">
                            <x-svgicon.moon />
                        </a>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'system'" href="javascript:;" class="flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60" @click="$store.app.toggleTheme('light')">
                            <x-svgicon.system />
                        </a>
                    </div>

                    <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                        <a href="javascript:;" class="relative group" @click="toggle()">
                            <span>
                                @if(Auth::user()->gender == 'male')
                                <img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100" src="/assets/images/avatar/avatar.png" alt="image" />
                                @else
                                <img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100" src="/assets/images/avatar/avatar-female.png" alt="image" />
                                @endif
                            </span>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 text-dark dark:text-white-dark top-11 !py-0 w-[230px] font-semibold dark:text-white-light/90">
                            <li>
                                <div class="flex items-center px-4 py-4">
                                    <div class="flex-none">
                                        @if(Auth::user()->gender == 'male')
                                        <img class="rounded-md w-10 h-10 object-cover" src="/assets/images/avatar/avatar.png" alt="image" />
                                        @else
                                        <img class="rounded-md w-10 h-10 object-cover" src="/assets/images/avatar/avatar-female.png" alt="image" />
                                        @endif
                                    </div>
                                    <div class="ltr:pl-4 rtl:pr-4 truncate">
                                        <div class="text-base">
                                            {{ Auth::user()->name }}
                                        </div>
                                        <div class="text-black/60  hover:text-primary dark:text-dark-light/60 dark:hover:text-white">
                                            {{ Auth::user()->national_id }}
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="border-t border-white-light dark:border-white-light/10">

                                <div class="p-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();" class="text-red-400 text-xs flex gap-1">
                                            <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0 rotate-90" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5" d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                            تسجيل الخروج
                                        </a>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
             
            </div>
        </div>

    </div>
</header>