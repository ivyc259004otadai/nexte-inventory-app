@extends('layouts.app')

@section('title', '在庫確認リスト')

@section('content')
<section class="inventory-page">
    <header class="inventory-header">
        <div>
            <h1 class="inventory-header__title">在庫確認リスト</h1>
            <p class="inventory-header__description">表示対象 {{ $inventoryCount }} 件</p>
        </div>
        <p class="inventory-header__last-updated">
            最終更新: {{ $lastUpdatedAt }}
        </p>
    </header>

    <form method="GET" class="inventory-toolbar">
        <label class="inventory-search-field">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.7" />
                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="商品名・SKUで検索...">
        </label>

        <label class="inventory-filter-select inventory-filter-select--store">
            <select name="store">
                <option value="">店舗</option>
                @foreach ($stores as $store)
                <option value="{{ $store->store_code }}" @selected($filters['store']===$store->store_code)>{{ $store->store_name }}</option>
                @endforeach
            </select>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            </svg>
        </label>

        <label class="inventory-filter-select inventory-filter-select--status">
            <select name="status">
                <option value="">在庫状態</option>
                <option value="D" @selected($filters['status']==='D' )>欠品</option>
                <option value="C" @selected($filters['status']==='C' )>在庫過少</option>
                <option value="A" @selected($filters['status']==='A' )>適正在庫</option>
                <option value="B" @selected($filters['status']==='B' )>在庫過多</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
            </svg>
        </label>

        <label class="inventory-filter-select inventory-filter-select--category">
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

        <div class="inventory-toolbar__actions">
            <button type="submit" class="button-primary button-primary--small">
                絞り込み
            </button>
            <a class="inventory-toolbar__clear" href="{{ route('inventory.list') }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 5h16l-6.5 7.4V18l-3 1.7v-7.3L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                </svg>
                フィルタークリア
            </a>
        </div>
    </form>

    <section class="inventory-results" aria-label="在庫確認リスト一覧">
        <div class="inventory-table-card">
            <div class="inventory-table-scroll">
                <table class="inventory-table">
                    <colgroup>
                        <col style="width: 7%">
                        <col style="width: 8%">
                        <col style="width: 18%">
                        <col style="width: 11%">
                        <col style="width: 8%">
                        <col style="width: 8%">
                        <col style="width: 7%">
                        <col style="width: 8%">
                        <col style="width: 8%">
                        <col style="width: 8%">
                        <col style="width: 8%">
                    </colgroup>
                    <thead>
                        <tr class="inventory-table__row">
                            <th>在庫状態</th>
                            <th>発注判断</th>
                            <th>商品名</th>
                            <th>商品コード</th>
                            <th>カラー</th>
                            <th>サイズ</th>
                            <th>店舗</th>
                            <th>現在庫</th>
                            <th>基準在庫</th>
                            <th>週間販</th>
                            <th>差分</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventoryRows as $row)
                        <tr
                            class="inventory-table__row"
                            data-href="{{ route('inventory.edit', $row['id']) }}">

                            <td class="inventory-table__priority">
                                <span
                                    class="inventory-priority inventory-priority--{{ $row['status'] }}"
                                    aria-label="{{ $row['status_label'] }}">

                                    @switch($row['status'])
                                    @case('D')
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path
                                            d="M6.5 6.5 17.5 17.5M17.5 6.5 6.5 17.5"
                                            stroke="currentColor"
                                            stroke-width="2.6"
                                            stroke-linecap="round" />
                                    </svg>
                                    @break

                                    @case('C')
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path
                                            d="M12 5v9"
                                            stroke="currentColor"
                                            stroke-width="2.8"
                                            stroke-linecap="round" />
                                        <circle
                                            cx="12"
                                            cy="18"
                                            r="1.5"
                                            fill="currentColor" />
                                    </svg>
                                    @break

                                    @case('B')
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path
                                            d="M12 4 3.5 19h17L12 4Z"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linejoin="round" />
                                    </svg>
                                    @break

                                    @default
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="8.5"
                                            stroke="currentColor"
                                            stroke-width="2" />
                                    </svg>
                                    @endswitch
                                </span>
                            </td>

                            <td>
                                <span class="order-decision order-decision--{{ $row['order_decision_tone'] }}">
                                    {{ $row['order_decision_label'] }}
                                </span>
                            </td>
                            <td class="inventory-table__name">
                                <a
                                    class="inventory-table__name-link"
                                    href="{{ route('inventory.edit', $row['id']) }}">
                                    {{ $row['product_name'] }}
                                </a>
                            </td>
                            <td class="inventory-table__product-code">{{ $row['product_code'] }}</td>
                            <td>{{ $row['color'] }}</td>
                            <td><span class="inventory-size-tag">{{ $row['size'] }}</span></td>
                            <td>{{ $row['store_name'] }}</td>
                            <td class="inventory-table__stock inventory-table__stock--{{ $row['status'] }}">{{ $row['current_stock'] }}</td>
                            <td>{{ $row['required_stock'] }}</td>
                            <td>{{ $row['weekly_sales'] }}</td>
                            <td class="inventory-table__diff {{ $row['difference'] >= 0 ? 'inventory-table__diff--positive' : 'inventory-table__diff--negative' }}">
                                {{ $row['difference_label'] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11">
                                <div class="inventory-empty">該当する在庫データがありません。</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="inventory-results__footer">
            <div class="inventory-legend" aria-label="在庫状態の凡例">
                <span class="inventory-legend__title">在庫状態</span>
                <span class="inventory-legend__item">
                    <span class="inventory-legend__symbol inventory-legend__symbol--A" aria-hidden="true">○</span>
                    適正在庫
                </span>
                <span class="inventory-legend__item">
                    <span class="inventory-legend__symbol inventory-legend__symbol--B" aria-hidden="true">△</span>
                    在庫過多
                </span>
                <span class="inventory-legend__item">
                    <span class="inventory-legend__symbol inventory-legend__symbol--C" aria-hidden="true">!</span>
                    在庫過少
                </span>
                <span class="inventory-legend__item">
                    <span class="inventory-legend__symbol inventory-legend__symbol--D" aria-hidden="true">×</span>
                    欠品
                </span>
            </div>

            <nav class="inventory-pagination" aria-label="在庫一覧のページ送り">
                @if ($inventoryRows->onFirstPage())
                <span class="inventory-pagination__button is-disabled" aria-disabled="true">← 前へ</span>
                @else
                <a
                    class="inventory-pagination__button"
                    href="{{ $inventoryRows->previousPageUrl() }}">
                    ← 前へ
                </a>
                @endif

                <span class="inventory-pagination__status">
                    {{ $inventoryRows->currentPage() }} / {{ $inventoryRows->lastPage() }}ページ
                </span>

                @if ($inventoryRows->hasMorePages())
                <a
                    class="inventory-pagination__button"
                    href="{{ $inventoryRows->nextPageUrl() }}">
                    次へ →
                </a>
                @else
                <span class="inventory-pagination__button is-disabled" aria-disabled="true">次へ →</span>
                @endif
            </nav>
        </div>
    </section>
</section>
@endsection