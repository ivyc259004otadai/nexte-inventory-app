@props(['product'])

@php
$statusTone = $product['status_tone'];
@endphp

<article class="product-card product-card--{{ $statusTone }}">
    <div class="product-card__header">
        <x-status-badge :status="$product['status']" :label="$product['status_label']" />
        <span class="product-card__sku">{{ $product['sku'] }}</span>
    </div>

    <h3 class="product-card__name">{{ $product['product_name'] }}</h3>

    <div class="product-card__chips" aria-label="商品属性">
        <span class="chip">{{ $product['color'] }}</span>
        <span class="chip">{{ $product['size'] }}</span>
        <span class="chip">{{ $product['store_name'] }}</span>
    </div>

    <dl class="product-card__metrics">
        <div>
            <dt>現在庫</dt>
            <dd class="product-card__metric-value {{ $product['status'] === 'D' ? 'product-card__metric-value--danger' : '' }}">{{ $product['current_stock'] }}</dd>
        </div>
        <div>
            <dt>基準在庫</dt>
            <dd>{{ $product['required_stock'] }}</dd>
        </div>
        <div>
            <dt>週間販売</dt>
            <dd>{{ $product['weekly_sales'] }}</dd>
        </div>
    </dl>

    <div class="product-card__footer">
        <div>
            <p class="product-card__footer-label">補充候補数</p>
            <p class="product-card__footer-value product-card__footer-value--{{ $statusTone }}">+{{ $product['supplement_candidates'] }}点</p>
        </div>

        <a href="{{ route('inventory.edit', ['inventory' => $product['inventory_id'], 'from' => 'dashboard']) }}" class="product-card__button">
            詳細を見る →
        </a>
    </div>
</article>