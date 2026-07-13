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