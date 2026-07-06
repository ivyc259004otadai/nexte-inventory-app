@extends('layouts.app')

@section('title', '在庫詳細・編集')

@section('content')
<section class="detail-page">
    <div class="detail-page__crumbs">
        @if (request('form') === 'dashboard')
        <a href="{{ route('dashboard') }}">← 戻る</a>
        @else
        <a href="{{ route('inventory.list') }}">← 戻る</a>
        @endif
        <span>/</span>
        <span>{{ $productName }}</span>
    </div>

    <header class="detail-page__header">
        <div>
            <h1 class="detail-page__title">在庫詳細・編集</h1>
            <p class="detail-page__subtitle">在庫数・基準値・週間販売数を編集できます</p>
        </div>
        <x-status-badge :status="$status['status']" :label="$status['label']" />
    </header>

    <div class="detail-grid">
        <aside class="detail-stack">
            <article class="panel-card">
                <p class="panel-card__eyebrow">商品基本情報</p>
                <h2 class="panel-card__name">{{ $productName }}</h2>
                <p class="panel-card__sku">{{ $sku }}</p>

                <dl class="info-list">
                    <div>
                        <dt>カラー</dt>
                        <dd>{{ $color }}</dd>
                    </div>
                    <div>
                        <dt>サイズ</dt>
                        <dd>{{ $size }}</dd>
                    </div>
                    <div>
                        <dt>店舗</dt>
                        <dd>{{ $storeName }}</dd>
                    </div>
                    <div>
                        <dt>カテゴリ</dt>
                        <dd>{{ $inventory->productVariant?->product?->category ?? '-' }}</dd>
                    </div>
                </dl>
            </article>

            <article class="panel-card panel-card--status panel-card--{{ $status['tone'] }}">
                <p class="panel-card__eyebrow">在庫状態サマリー</p>
                <div class="status-hero">
                    <x-status-badge :status="$status['status']" :label="$status['label']" />
                </div>

                <dl class="summary-metrics">
                    <div>
                        <dt>差分（現在-基準）</dt>
                        <dd class="{{ $difference < 0 ? 'is-danger' : '' }}">{{ $difference }}</dd>
                    </div>
                    <div>
                        <dt>在庫週数</dt>
                        <dd>{{ $weeksText }}</dd>
                    </div>
                    <div>
                        <dt>補充候補数</dt>
                        <dd class="is-danger">{{ $supplementCandidates }}点</dd>
                    </div>
                </dl>

                <div class="status-scale">
                    <span>現在庫 {{ $inventory->current_stock }}</span>
                    <span>基準 {{ $inventory->required_stock }}</span>
                </div>
            </article>
        </aside>

        <article class="panel-card panel-card--form">
            <h2 class="panel-card__title">在庫数・設定の編集</h2>

            <form method="POST" class="detail-form">
                @csrf
                <div class="form-grid">
                    <label class="field-group">
                        <span>現在庫数</span>
                        <input type="number" min="0" name="current_stock" value="{{ old('current_stock', $inventory->current_stock) }}">
                    </label>
                    <label class="field-group">
                        <span>基準在庫数</span>
                        <input type="number" min="0" name="required_stock" value="{{ old('required_stock', $inventory->required_stock) }}">
                    </label>
                    <label class="field-group">
                        <span>週間販売数</span>
                        <input type="number" min="0" name="weekly_sales" value="{{ old('weekly_sales', $inventory->weekly_sales) }}">
                    </label>
                    <label class="field-group">
                        <span>最終確認日</span>
                        <input type="date" name="last_checked_at" value="{{ old('last_checked_at', optional($inventory->last_checked_at)->format('Y-m-d')) }}">
                    </label>
                </div>

                <label class="field-group field-group--full">
                    <span>備考</span>
                    <textarea name="note" rows="5" placeholder="補充メモや確認事項など...">{{ old('note', $inventory->note) }}</textarea>
                </label>

                <div class="form-actions">
                    <button type="submit" class="button-primary">変更を保存する</button>

                    @if (request('form') === 'dashboard')
                    <a class="button-secondary" href="{{ route('dashboard') }}">戻る</a>
                    @else
                    <a class="button-secondary" href="{{ route('inventory.list') }}">戻る</a>
                    @endif
                </div>
            </form>
        </article>
    </div>
</section>
@endsection