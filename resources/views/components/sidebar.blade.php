@props([
'displayScope' => '全店舗',
'currentPage' => 'dashboard',
])

<aside class="sidebar">
    <div class="sidebar__brand">
        <div class="sidebar__brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4.5 7.5L12 3l7.5 4.5v9L12 21l-7.5-4.5v-9Z" stroke="currentColor" stroke-width="1.6" />
                <path d="M7.2 10.5h9.6v6.3H7.2v-6.3Z" stroke="currentColor" stroke-width="1.6" />
                <path d="M9 10.5V8.8a3 3 0 0 1 6 0v1.7" stroke="currentColor" stroke-width="1.6" />
            </svg>
        </div>
        <div>
            <div class="sidebar__brand-name">Nexte</div>
            <div class="sidebar__brand-subtitle">在庫管理システム</div>
        </div>
    </div>

    <nav class="sidebar__nav" aria-label="メインメニュー">
        <div class="sidebar__group-title">メニュー</div>

        <a class="sidebar__item {{ $currentPage === 'dashboard' ? 'sidebar__item--active' : '' }}" href="{{ route('dashboard') }}" @if ($currentPage==='dashboard' ) aria-current="page" @endif>
            <span class="sidebar__item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" stroke="currentColor" stroke-width="1.7" />
                </svg>
            </span>
            <span>ダッシュ<br class="sidebar__tablet-break">ボード</span>
            <span class="sidebar__chevron" aria-hidden="true">›</span>
        </a>

        <a class="sidebar__item {{ $currentPage === 'inventory' ? 'sidebar__item--active' : '' }}" href="{{ route('inventory.list') }}" @if ($currentPage==='inventory' ) aria-current="page" @endif>
            <span class="sidebar__item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 3 4 7.5 12 12l8-4.5L12 3Z" stroke="currentColor" stroke-width="1.7" />
                    <path d="M4 12l8 4.5 8-4.5" stroke="currentColor" stroke-width="1.7" />
                    <path d="M4 16.5 12 21l8-4.5" stroke="currentColor" stroke-width="1.7" />
                </svg>
            </span>
            <span>在庫確認</span>
            <span class="sidebar__chevron" aria-hidden="true">›</span>
        </a>

        <div class="sidebar__group-title sidebar__group-title--spaced">管理</div>

        <a class="sidebar__item {{ $currentPage === 'product' ? 'sidebar__item--active' : '' }}" href="{{ route('product.manage') }}" @if ($currentPage==='product' ) aria-current="page" @endif>
            <span class="sidebar__item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M5 19V5m0 14h14" stroke="currentColor" stroke-width="1.7" />
                    <path d="M8.5 15v-4m4 4V7m4 8v-6" stroke="currentColor" stroke-width="1.7" />
                </svg>
            </span>
            <span>商品管理</span>
            <span class="sidebar__chevron" aria-hidden="true">›</span>
        </a>

        <a class="sidebar__item {{ $currentPage === 'store' ? 'sidebar__item--active' : '' }}" href="{{ route('store.manage') }}" @if ($currentPage==='store' ) aria-current="page" @endif>
            <span class="sidebar__item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4.5 20V8.5l7.5-4 7.5 4V20" stroke="currentColor" stroke-width="1.7" />
                    <path d="M8 20v-6h8v6" stroke="currentColor" stroke-width="1.7" />
                </svg>
            </span>
            <span>店舗管理</span>
            <span class="sidebar__chevron" aria-hidden="true">›</span>
        </a>
    </nav>

    <div class="sidebar__footer">
        <div class="sidebar__footer-label"><span>表示</span><span>範囲：</span></div>
        <div class="sidebar__footer-store">{{ $displayScope }}</div>
    </div>
</aside>
