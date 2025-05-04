<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    @if (auth()->user()->user_type === 'admin')
        <x-sidebar.link title="Dashboard" href="{{ route(auth()->user()->getDashboardRouteAttribute()) }}"
            :isActive="request()->routeIs(auth()->user()->getDashboardRouteAttribute())">
            <x-slot name="icon">
                <i class="fa-solid fa-house"></i>
            </x-slot>
        </x-sidebar.link>
    @elseif (auth()->user()->user_type === 'patient')
        <x-sidebar.link title="Home" href="{{ route(auth()->user()->getDashboardRouteAttribute()) }}" :isActive="request()->routeIs(auth()->user()->getDashboardRouteAttribute())">
            <x-slot name="icon">
                <i class="fa-solid fa-house"></i>
            </x-slot>
        </x-sidebar.link>
    @elseif (auth()->user()->user_type === 'doctor')
        <x-sidebar.link title="Dashboard" href="{{ route(auth()->user()->getDashboardRouteAttribute()) }}"
            :isActive="request()->routeIs(auth()->user()->getDashboardRouteAttribute())">
            <x-slot name="icon">
                <i class="fa-solid fa-house transition-transform duration-300 transform group-hover:scale-110"></i>
            </x-slot>
        </x-sidebar.link>
    @endif

    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500 font-semibold uppercase tracking-wider mt-2 mb-1">
        @if (auth()->user()->user_type === 'doctor')
        <div class="flex items-center">
            <div class="w-1 h-4 bg-blue-500 rounded-r-full mr-2"></div>
            <span>Medical Services</span>
        </div>
        @else
        Links
        @endif
    </div>
    @if (auth()->user()->user_type === 'admin')
        <x-sidebar.link title="Doctors" href="{{ route('admin.doctor') }}" :isActive="request()->routeIs('admin.doctor')">
            <x-slot name="icon">
                <i class="fa-solid fa-user-doctor"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Patients" href="{{ route('admin.patient') }}" :isActive="request()->routeIs('admin.patient')">
            <x-slot name="icon">
                <i class="fa-solid fa-bed-pulse"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Apointments" href="{{ route('admin.appointments') }}" :isActive="request()->routeIs('admin.appointments')">
            <x-slot name="icon">
                <i class="fa-regular fa-calendar-check"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Schedules" href="{{ route('admin.schedules') }}" :isActive="request()->routeIs('admin.schedules')">
            <x-slot name="icon">
                <i class="fa-solid fa-calendar-days"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Specialities" href="{{ route('admin.specialities') }}" :isActive="request()->routeIs('admin.specialities')">
            <x-slot name="icon">
                <i class="fa-solid fa-briefcase"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Doctors Applies" href="{{ route('admin.apply') }}" :isActive="request()->routeIs('admin.apply')">
            <x-slot name="icon">
                <i class="fa-solid fa-file-contract"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Travel Analytics" href="{{ route('admin.doctor.travel-times') }}" :isActive="request()->routeIs('admin.doctor.travel-times')">
            <x-slot name="icon">
                <i class="fa-solid fa-route"></i>
            </x-slot>
        </x-sidebar.link>
    @endif
    @if (auth()->user()->user_type === 'patient')
        <x-sidebar.link title="Doctor List" href="{{ route('patiens.doctors') }}" :isActive="request()->routeIs('patiens.doctors')">
            <x-slot name="icon">
                <i class="fa-solid fa-user-doctor"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="My Appointments" href="{{ route('patiens.my.appointments', authUser()->patient->id) }}"
            :isActive="request()->routeIs('patiens.my.appointments')">
            <x-slot name="icon">
                <i class="fa-regular fa-calendar-check"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="My Ratings" href="{{ route('patient.my.ratings') }}"
            :isActive="request()->routeIs('patient.my.ratings')">
            <x-slot name="icon">
                <i class="fa-solid fa-star text-yellow-500"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Health Articles" href="{{ route('patiens.health.tips.view', authUser()->patient->id) }}"
            :isActive="request()->routeIs('patiens.health.tips.view')">
            <x-slot name="icon">
                <i class="fa-solid fa-staff-snake"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Emergency Contacts" href="{{ route('patients.emergency.contacts.view') }}"
            :isActive="request()->routeIs('patients.emergency.contacts.view')">
            <x-slot name="icon">
                <i class="fa-solid fa-phone-volume"></i>
            </x-slot>
        </x-sidebar.link>
    @endif

    @if (auth()->user()->user_type === 'doctor')
        <x-sidebar.link title="Appointments" href="{{ route('doctor.appointments') }}" :isActive="request()->routeIs('doctor.appointments') && !request()->has('travel')">
            <x-slot name="icon">
                <i class="fa-regular fa-calendar-check transition-transform duration-300 transform group-hover:scale-110 text-blue-500"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Travel Tracking" href="{{ route('doctor.travel-tracking') }}" :isActive="request()->routeIs('doctor.travel-tracking') || (request()->routeIs('doctor.appointments') && request()->has('travel'))">
            <x-slot name="icon">
                <i class="fa-solid fa-route transition-transform duration-300 transform group-hover:scale-110 text-indigo-500"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="Schedules" href="{{ route('doctor.schedule') }}" :isActive="request()->routeIs('doctor.schedule')">
            <x-slot name="icon">
                <i class="fa-solid fa-calendar-days transition-transform duration-300 transform group-hover:scale-110 text-emerald-500"></i>
            </x-slot>
        </x-sidebar.link>
        
        <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500 font-semibold uppercase tracking-wider mt-4 mb-1">
            <div class="flex items-center">
                <div class="w-1 h-4 bg-purple-500 rounded-r-full mr-2"></div>
                <span>Patient Management</span>
            </div>
        </div>
        
        <x-sidebar.link title="My patients" href="{{ route('doctor.mypatients') }}" :isActive="request()->routeIs('doctor.mypatients')">
            <x-slot name="icon">
                <i class="fa-solid fa-bed-pulse transition-transform duration-300 transform group-hover:scale-110 text-purple-500"></i>
            </x-slot>
        </x-sidebar.link>
        <x-sidebar.link title="My Ratings" href="{{ route('doctor.myreviews') }}" :isActive="request()->routeIs('doctor.myreviews')">
            <x-slot name="icon">
                <i class="fa-solid fa-star transition-transform duration-300 transform group-hover:scale-110 text-yellow-500"></i>
            </x-slot>
        </x-sidebar.link>

    @endif
</x-perfect-scrollbar>
