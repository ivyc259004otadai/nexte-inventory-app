@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<section class="list-page">
    <header class="list-page__header">
        <div>
            <h1 class="list-page__title">商品管理</h1>
            <p class="list-page__meta">登録商品 {{ $productCount }}件　|　最終更新：{{ $lastUpdatedAt }}</p>
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
                <option value="">すべて</option>
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

        <a class="button-primary button-primary--small" href="{{ route('product.manage') }}">
            クリア
        </a>

        <div class="inventory-toolbar__meta">
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
                    <th class="data-table__action-heading">操作</th>
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
                        <span class="inventory-status inventory-status--B">
                            <span class="inventory-status__dot"></span>
                            取扱停止
                        </span>
                        @break
                        @endswitch

                    </td>
                    <td>{{ optional($product->created_at)->format('Y-m-d') }}</td>
                    <td class="data-table__action-cell"><a class="button-outline button-outline--blue" href="{{ route('product.form', $product) }}">編集</a></td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection