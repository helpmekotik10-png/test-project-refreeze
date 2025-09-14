<x-layout.app>
    <x-slot:mainContent>

        <!-- Разделитель -->
        <div class="row my-4">
            <div class="col-12">
                <h2>Все товары</h2>
                <hr>
            </div>
        </div>

        <!-- Форма сортировки и количества -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="GET" class="d-flex flex-wrap gap-3 align-items-center">
                    <!-- Сортировка -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Сортировать:</span>
                        <select name="sort" class="form-select form-select-sm" style="width: auto"
                            onchange="this.form.submit()">
                            <option value="name_asc" {{ $sort==='name_asc' ? 'selected' : '' }}>Имя ↑</option>
                            <option value="name_desc" {{ $sort==='name_desc' ? 'selected' : '' }}>Имя ↓</option>
                            <option value="price_asc" {{ $sort==='price_asc' ? 'selected' : '' }}>Цена ↑</option>
                            <option value="price_desc" {{ $sort==='price_desc' ? 'selected' : '' }}>Цена ↓</option>
                        </select>
                    </div>

                    <!-- Количество товаров на странице -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Показывать:</span>
                        <select name="per_page" class="form-select form-select-sm" style="width: auto"
                            onchange="this.form.submit()">
                            @foreach([6, 12, 18] as $num)
                            <option value="{{ $num }}" {{ $perPage==$num ? 'selected' : '' }}>{{ $num }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Сохраняем ВСЕ остальные GET-параметры (search, filters и т.д.) -->
                    @foreach(request()->except(['sort', 'per_page', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>
        </div>

        <!-- Группы как кнопки -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($topGroups as $group)
                    <a href="{{ route('group.show', $group->id) }}"
                        class="btn btn-outline-primary d-flex align-items-center text-nowrap px-3 py-2">
                        {{ $group->name }}
                        <span
                            class="badge bg-primary text-white rounded-circle ms-2 d-flex align-items-center justify-content-center"
                            style="width: 22px; height: 22px; font-size: 0.75rem;">
                            {{ $group->product_count }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Сетка товаров -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-4">
                <div class="card h-100 border-light shadow-sm hover-lift">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title line-clamp-2" title="{{ $product->name }}"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->name }}
                        </h6>
                        <div class="mt-auto text-end pt-2">
                            <strong class="text-success fs-5">
                                {{ number_format($product->price?->price ?? 0, 2) }} ₽
                            </strong>
                            <br>
                            <a href="{{ route('product.show', $product->id) }}"
                                class="btn btn-sm btn-outline-primary mt-2 px-3">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-4">
                    <strong>Пока нет товаров.</strong>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        <div class="row mt-5">
            <div class="col">
                {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </x-slot:mainContent>
</x-layout.app>