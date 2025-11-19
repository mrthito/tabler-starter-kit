{{-- Admin Layout Menu Item View - Tabler UI Style --}}
@if (isset($menus) && is_array($menus))
    {{-- Render multiple menu items from a group --}}
    @foreach ($menus as $menuItem)
        @if (\App\Facades\Menu::canAccess($menuItem, $user))
            <li
                class="nav-item @if ($menuItem->hasChildren()) dropdown @endif @if (\App\Facades\Menu::isActive($menuItem)) active @endif">
                <a class="nav-link @if ($menuItem->hasChildren()) dropdown-toggle @endif @if (\App\Facades\Menu::isActive($menuItem)) active @endif"
                    href="{{ $menuItem->getUrl() }}"
                    @if ($menuItem->hasChildren()) data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-haspopup="true" aria-expanded="{{ \App\Facades\Menu::hasActiveChildren($menuItem) ? 'true' : 'false' }}" @endif>
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        @if ($menuItem->getIcon())
                            <i class="icon icon-1 ti ti-{{ $menuItem->getIcon() }}"></i>
                        @endif
                    </span>
                    <span class="nav-link-title">{{ $menuItem->getTitle() }}</span>
                </a>
                @if ($menuItem->hasChildren())
                    <div class="dropdown-menu @if (\App\Facades\Menu::hasActiveChildren($menuItem)) show @endif">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach ($menuItem->getChildren() as $child)
                                    @if (\App\Facades\Menu::canAccess($child, $user))
                                        <a class="dropdown-item @if (\App\Facades\Menu::isActive($child)) active @endif"
                                            href="{{ $child->getUrl() }}">
                                            {{ $child->getTitle() }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @endif
    @endforeach
@elseif (isset($menu))
    {{-- Render single menu item --}}
    <li
        class="nav-item @if ($menu->hasChildren()) dropdown @endif @if (\App\Facades\Menu::isActive($menu)) active @endif">
        <a class="nav-link @if ($menu->hasChildren()) dropdown-toggle @endif @if (\App\Facades\Menu::isActive($menu)) active @endif"
            href="{{ $menu->getUrl() }}"
            @if ($menu->hasChildren()) data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-haspopup="true" aria-expanded="{{ \App\Facades\Menu::hasActiveChildren($menu) ? 'true' : 'false' }}" @endif>
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                @if ($menu->getIcon())
                    <i class="icon icon-1 ti ti-{{ $menu->getIcon() }}"></i>
                @endif
            </span>
            <span class="nav-link-title">{{ $menu->getTitle() }}</span>
        </a>
        @if ($menu->hasChildren())
            <div class="dropdown-menu @if (\App\Facades\Menu::hasActiveChildren($menu)) show @endif">
                <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                        @foreach ($menu->getChildren() as $child)
                            @if (\App\Facades\Menu::canAccess($child, $user))
                                <a class="dropdown-item @if (\App\Facades\Menu::isActive($child)) active @endif"
                                    href="{{ $child->getUrl() }}">
                                    {{ $child->getTitle() }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </li>
@endif
