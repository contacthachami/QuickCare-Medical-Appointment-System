@php
    $sizeClasses = [
        'xs' => 'w-8 h-8',
        'sm' => 'w-10 h-10',
        'md' => 'w-16 h-16',
        'lg' => 'w-24 h-24',
        'xl' => 'w-32 h-32',
        '2xl' => 'w-48 h-48',
    ];
    
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        'full' => 'rounded-full',
    ];
    
    $sizeClass = isset($sizeClasses[$size]) ? $sizeClasses[$size] : $sizeClasses['md'];
    $roundedClass = isset($roundedClasses[$rounded]) ? $roundedClasses[$rounded] : $roundedClasses['full'];
    
    $containerClassFinal = $sizeClass . ' ' . $roundedClass . ' overflow-hidden ' . $containerClass;
    
    // Generate initials if no image
    $initials = '';
    if (is_object($user) && isset($user->name)) {
        $nameParts = explode(' ', $user->name);
        $firstName = $nameParts[0] ?? '';
        $lastName = end($nameParts);
        $initials = strtoupper(substr($firstName, 0, 1) . (count($nameParts) > 1 ? substr($lastName, 0, 1) : ''));
    }
@endphp

@if (is_object($user) && isset($user->img) && $user->img)
    <div class="{{ $containerClassFinal }} profile-image-container border-2 border-white shadow-sm">
        <img src="{{ asset('storage/profile_pictures/' . $user->img) }}" 
             alt="{{ $user->name ?? 'Profile' }}" 
             class="w-full h-full object-cover object-center profile-image {{ $imgClass }}">
    </div>
@else
    <div class="{{ $containerClassFinal }} flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm">
        <div class="absolute inset-0 bg-white opacity-10 rounded-full" style="background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3) 0%, transparent 70%);"></div>
        <span class="relative z-10 text-white font-semibold" style="font-size: calc({{ substr($sizeClass, 2, 2) }}px * 0.4);">{{ $initials }}</span>
    </div>
@endif 