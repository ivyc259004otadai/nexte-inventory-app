@extends('layouts.app')

@section('title', '店舗管理')

@section('content')
<section class="list-page">
    <header class="list-page__header">
        <div>
            <h1 class="list-page__title">店舗管理</h1>
            <p class="list-page__meta">登録店舗 {{ $storeCount }}件　|　最終更新：{{ $lastUpdatedAt }}</p>
        </div>
    </header>

    <form method="GET" class="list-toolbar">
        <label class="inventory-search-field">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.7" />
                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="店舗名・コード・所在地で検索...">
        </label>
        <label class="inventory-filter-select">
            <select name="status">
                <option value="">すべて</option>
                <option value="open" @selected($filters['status']==='open' )>営業中</option>
                <option value="suspended" @selected($filters['status']==='suspended' )>休業</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            </svg>
        </label>

        <button type="submit" class="button-primary button-primary--small">
            絞り込み
        </button>

        <a class="button-primary button-primary--small" href="{{ route('store.manage') }}">
            クリア
        </a>

        <div class="inventory-toolbar__meta">
            <a class="button-primary button-primary--small" href="{{ route('store.form') }}">＋ 新規店舗登録</a>
        </div>
    </form>

    <section class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>店舗コード</th>
                    <th>店舗名</th>
                    <th>所在地</th>
                    <th>電話番号</th>
                    <th>店舗状態</th>
                    <th class="data-table__action-heading">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                <tr>
                    <td>{{ $store->store_code }}</td>
                    <td class="data-table__name">{{ $store->store_name }}</td>
                    <td>{{ $store->address ?? '-' }}</td>
                    <td>{{ $store->phone_number ?? '-' }}</td>
                    <td>
                        @switch($store->status)
                        @case('open')
                        <span class="inventory-status inventory-status--A">
                            <span class="inventory-status__dot"></span>
                            営業中
                        </span>
                        @break

                        @case('suspended')
                        <span class="inventory-status inventory-status--B">
                            <span class="inventory-status__dot"></span>
                            休業
                        </span>
                        @break

                        @case('closed')
                        <span class="inventory-status inventory-status--D">
                            <span class="inventory-status__dot"></span>
                            閉店
                        </span>
                        @break

                        @default
                        <span class="inventory-status inventory-status--D">
                            <span class="inventory-status__dot"></span>
                            未設定
                        </span>
                        @endswitch
                    </td>
                    <td class="data-table__action-cell">
                        <a class="button-outline button-outline--blue" href="{{ route('store.form', $store) }}">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection