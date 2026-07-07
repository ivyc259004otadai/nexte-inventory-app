@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
<section class="form-page">
    <div class="detail-page__crumbs">
        <a href="{{ route('store.manage') }}">← 戻る</a>
        <span>/</span>
        <span>{{ $pageTitle }}</span>
    </div>

    <h1 class="list-page__title">{{ $pageTitle }}</h1>

    <article class="form-card form-card--narrow">
        <form method="POST" class="detail-form">
            @csrf
            <div class="form-grid form-grid--single">
                <label class="field-group">
                    <span>店舗コード</span>
                    <input type="text" name="store_code" value="{{ old('store_code', $store->store_code ?? '') }}" placeholder="例：SHB-006">
                </label>
                <label class="field-group">
                    <span>店舗名</span>
                    <input type="text" name="store_name" value="{{ old('store_name', $store->store_name ?? '') }}" placeholder="例：渋谷店">
                </label>
                <label class="field-group">
                    <span>所在地</span>
                    <input type="text" name="address" value="{{ old('address', $store->address ?? '') }}" placeholder="例：東京都渋谷区道玄坂1-2-3">
                </label>
                <label class="field-group">
                    <span>電話番号</span>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $store->phone_number ?? '') }}" placeholder="例：03-1234-5678">
                </label>
                <div class="field-group">
                    <span>店舗状態</span>
                    <div class="radio-row">
                        <label><input type="radio" name="status" value="open" @checked(old('status', $store->status ?? 'open') === 'open')> 営業中</label>
                        <label><input type="radio" name="status" value="suspended" @checked(old('status', $store->status ?? 'open') === 'suspended')> 休業</label>
                        <label><input type="radio" name="status" value="closed" @checked(old('status', $store->status ?? 'open') === 'closed')> 閉店</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button-primary">{{ $store ? '更新する' : '登録する' }}</button>
                <a class="button-secondary" href="{{ route('store.manage') }}">キャンセル</a>
            </div>
        </form>
    </article>
</section>
@endsection