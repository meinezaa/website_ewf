<svg viewBox="0 0 24 24" aria-hidden="true">
@switch($name)
  @case('dashboard')
    <rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>
    @break
  @case('users')
    <circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c.6-3.4 3.2-5 6.5-5s5.9 1.6 6.5 5"/><path d="M16 4.6a3.5 3.5 0 0 1 0 6.8M18 15.3c2 .6 3.1 2.2 3.5 4.7"/>
    @break
  @case('clock')
    <path d="M12 7v5l3 2"/><circle cx="12" cy="12" r="9"/>
    @break
  @case('user')
    <circle cx="12" cy="8" r="4"/><path d="M4 21c.8-4 4-6 8-6s7.2 2 8 6"/>
    @break
  @case('logout')
    <path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
    @break
  @case('shield')
    <path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6z"/>
    @break
  @case('briefcase')
    <rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M3 13h18"/>
    @break
  @case('card')
    <rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.5-1.5 1.7-2 3-2s2.5.5 3 2M15 10h3M15 13h3"/>
    @break
  @case('cap')
    <path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/>
    @break
@endswitch
</svg>
