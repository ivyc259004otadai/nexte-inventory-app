@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
<section class="dashboard-page">
    <header class="dashboard-header">
        <div>
            <p class="dashboard-header__eyebrow">本日の在庫データ</p>
            <h1 class="dashboard-header__title">ダッシュボード</h1>
            <p class="dashboard-header__meta">
                全{{ $inventoryCount }}件
                <span aria-hidden="true">｜</span>
                最終更新：{{ $lastUpdatedAt }}
            </p>
        </div>

        <div class="dashboard-header__actions">
            <div class="search-box" role="search" aria-label="商品名・SKU検索の見た目のみの表示">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.7" />
                    <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                </svg>
                <span>商品名・SKUで検索...</span>
            </div>
            <button type="button" class="icon-button" aria-label="通知">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 19.5a2.2 2.2 0 0 0 2.1-1.5h-4.2A2.2 2.2 0 0 0 12 19.5Z" fill="currentColor" />
                    <path d="M18 16.5H6c1.1-1 1.6-2.1 1.6-3.7v-2.2A4.4 4.4 0 0 1 12 6.2a4.4 4.4 0 0 1 4.4 4.4v2.2c0 1.6.5 2.7 1.6 3.7Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                    <circle cx="18.5" cy="5.5" r="3.2" fill="#ff4d4f" stroke="#fff" stroke-width="1.4" />
                </svg>
            </button>
        </div>
    </header>

    <section class="summary-grid" aria-label="状態別サマリー">
        @foreach ($summaryCards as $summaryCard)
        <x-summary-card
            :title="$summaryCard['title']"
            :count="$summaryCard['count']"
            :description="$summaryCard['description']"
            :tone="$summaryCard['tone']" />
        @endforeach
    </section>

    <section class="dashboard-section" aria-labelledby="priority-products-title">
        <div class="dashboard-section__header">
            <div>
                <h2 id="priority-products-title" class="dashboard-section__title">今日確認すべき商品</h2>
                <p class="dashboard-section__description">欠品・在庫過少の商品を優先表示しています</p>
            </div>
            <div class="dashboard-section__count">{{ $productCards->count() }}件</div>
        </div>

        @if ($productCards->isNotEmpty())
        <div class="product-grid">
            @foreach ($productCards as $productCard)
            <x-product-card :product="$productCard" />
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>今日確認すべき商品はありません。</p>
        </div>
        @endif
    </section>
</section>
@endsection