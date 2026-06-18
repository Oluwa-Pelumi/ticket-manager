<svg class="{{ $class ?? '' }}" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="logo-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="rgb(var(--primary))" />
            <stop offset="100%" stop-color="#8b5cf6" />
        </linearGradient>
    </defs>
    <path d="M20 20 L50 80 L80 20" stroke="url(#logo-gradient)" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" fill="none" />
    <circle cx="50" cy="45" r="6" fill="url(#logo-gradient)" />
</svg>
