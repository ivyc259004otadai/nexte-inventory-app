@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<section class="list-page">
    <header class="list-page__header">
        <div>
            <h1 class="list-page__title">商品管理</h1>
            <p class="list-page__meta">登録商品 {{ $productCount }}件　|　最終更新：{{ $lastUpdatedAt }}</p>
        </div>
        <div class="dashboard-header__actions">
            <div class="search-box search-box--compact" role="search" aria-label="商品名・SKU検索の見た目のみの表示">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.7" />
                    <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                </svg>
                <span>商品名・商品コードで検索...</span>
            </div>
            <button type="button" class="icon-button" aria-label="通知"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 19.5a2.2 2.2 0 0 0 2.1-1.5h-4.2A2.2 2.2 0 0 0 12 19.5Z" fill="currentColor" />
                    <path d="M18 16.5H6c1.1-1 1.6-2.1 1.6-3.7v-2.2A4.4 4.4 0 0 1 12 6.2a4.4 4.4 0 0 1 4.4 4.4v2.2c0 1.6.5 2.7 1.6 3.7Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                    <circle cx="18.5" cy="5.5" r="3.2" fill="#ff4d4f" stroke="#fff" stroke-width="1.4" />
                </svg></button>
        </div>
    </header>

    <form method="GET" class="list-toolbar">
        <label class="inventory-search-field">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.7" />
                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="商品名・商品コードで検索...">
        </label>
        <label class="inventory-filter-select">
            <select name="category">
                <option value="">カテゴリ</option>
                @foreach ($categories as $category)
                <option value="{{ $category }}" @selected($filters['category']===$category)>{{ $category }}</option>
                @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            </svg>
        </label>
        <label class="inventory-filter-select">
            <select name="status">
                <option value="">取扱状態</option>
                <option value="active" @selected($filters['status']==='active' )>取扱中</option>
                <option value="suspended" @selected($filters['status']==='suspended' )>取扱停止</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            </svg>
        </label>

        <button type="submit" class="button-primary button-primary--small">
            絞り込み
        </button>

        <div class="inventory-toolbar__meta">
            <span class="inventory-toolbar__count">{{ $productCount }}件</span>
            <a class="button-primary button-primary--small" href="{{ route('product.form') }}">＋ 新規商品登録</a>
        </div>
    </form>

    <section class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>カテゴリ</th>
                    <th>取扱状態</th>
                    <th>登録日</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td class="data-table__name">{{ $product->product_name }}</td>
                    <td><span class="pill">{{ $product->category ?? '-' }}</span></td>
                    <td>
                        @switch($product->status)
                        @case('active')
                        <span class="inventory-status inventory-status--A">
                            <span class="inventory-status__dot"></span>
                            取扱中
                        </span>
                        @break

                        @case('suspended')
                        <span class="inventory-status inventory-status--S">
                            <span class="inventory-status__dot"></span>
                            取扱停止
                        </span>
                        @break
                        @endswitch

                    </td>
                    <td>{{ optional($product->created_at)->format('Y-m-d') }}</td>
                    <td><a class="button-outline button-outline--blue" href="{{ route('product.form', $product) }}">編集</a></td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection