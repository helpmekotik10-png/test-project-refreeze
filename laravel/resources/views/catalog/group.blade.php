<x-layout.app>

    <x-slot:mainContent>


        <!-- Заголовок группы -->
        <div class="row my-4">
            <div class="col-12">
                <h1 class="h2 mb-0">{{ $group->name }}</h1>
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

                    <!-- Количество -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Показывать:</span>
                        <select name="per_page" class="form-select form-select-sm" style="width: auto"
                            onchange="this.form.submit()">
                            @foreach([6, 12, 18] as $num)
                            <option value="{{ $num }}" {{ $perPage==$num ? 'selected' : '' }}>{{ $num }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Сохраняем все остальные параметры -->
                    @foreach(request()->except(['sort', 'per_page', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>
        </div>

        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                @foreach($breadcrumbs as $crumb)
                <li class="breadcrumb-item">
                    <a href="{{ route('group.show', $crumb['id']) }}">{{ $crumb['name'] }} </a>
                </li>
                @endforeach
            </ol>
        </nav>
        <!-- Подгруппы -->
        @if($subgroups->isNotEmpty())
        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2">
                @foreach($subgroups as $sub)
                <a href="{{ route('group.show', $sub->id) }}"
                    class="btn btn-outline-secondary d-flex align-items-center text-nowrap px-3 py-2">
                    {{ $sub->name }}
                    <span
                        class="badge bg-secondary text-white rounded-circle ms-2 d-flex align-items-center justify-content-center"
                        style="width: 22px; height: 22px; font-size: 0.75rem;">
                        {{ $sub->product_count }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Сетка товаров -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-light">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-normal line-clamp-2"
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
                <div class="alert alert-info text-center py-5">
                    <strong>В этой категории пока нет товаров.</strong>
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