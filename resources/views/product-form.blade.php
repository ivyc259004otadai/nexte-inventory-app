@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
<section class="form-page">
    <div class="detail-page__crumbs">
        <a href="{{ route('product.manage') }}">← 戻る</a>
        <span>/</span>
        <span>{{ $pageTitle }}</span>
    </div>

    <h1 class="list-page__title">{{ $pageTitle }}</h1>

    <article class="form-card form-card--narrow">
        <form method="POST" class="detail-form">
            @csrf
            <div class="form-grid form-grid--single">
                <label class="field-group">
                    <span>商品コード</span>
                    <input type="text" name="product_code" value="{{ old('product_code', $product->product_code ?? '') }}" placeholder="例：PT-2025">
                </label>
                <label class="field-group">
                    <span>商品名</span>
                    <input type="text" name="product_name" value="{{ old('product_name', $product->product_name ?? '') }}" placeholder="例：スリムテーパードパンツ">
                </label>
                <label class="field-group">
                    <span>カテゴリ</span>
                    <select name="category">
                        <option value="">カテゴリを選択してください</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="field-group">
                    <span>取扱状態</span>
                    <div class="radio-row">
                        <label><input type="radio" name="status" value="active" @checked(old('status', $product->status ?? 'active') === 'active')> 取扱中</label>
                        <label><input type="radio" name="status" value="suspended" @checked(old('status', $product->status ?? 'active') === 'suspended')> 取扱停止</label>
                        <label><input type="radio" name="status" value="discontinued" @checked(old('status', $product->status ?? 'active') === 'discontinued')> 販売終了</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button-primary">{{ $product ? '更新する' : '登録する' }}</button>
                <a class="button-secondary" href="{{ route('product.manage') }}">キャンセル</a>
            </div>
        </form>
    </article>
</section>
@endsection