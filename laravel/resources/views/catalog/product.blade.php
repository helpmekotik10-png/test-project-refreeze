<x-layout.app>
    <x-slot:mainContent>
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                @foreach($breadcrumbs as $crumb)
                @if($crumb['id'])
                <li class="breadcrumb-item">
                    <a href="{{ route('group.show', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                </li>
                @else
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                @endif
                @endforeach
            </ol>
        </nav>

        <!-- Карточка товара -->
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <!-- Название -->
                <h1 class="card-title mb-1 fw-bold">{{ $product->name }}</h1>

                <!-- Цена -->
                <p class="fs-1 text-success fw-bold mt-3 mb-4">
                    {{ number_format($product->price?->price ?? 0, 2) }} ₽
                </p>

                <!-- Кнопки -->
                <div class="d-grid gap-3 d-md-flex mt-4">
                    <!-- Кнопка "Купить" -->
                    <a href="#" class="btn btn-primary px-5 py-2 fs-5 flex-fill">
                        💳 Купить
                    </a>

                    <!-- Кнопка "Назад" -->
                    <a href="javascript:history.back()" class="btn btn-outline-secondary px-4 py-2 flex-fill">
                        ← Назад
                    </a>
                </div>

                <!-- Дополнительно: сообщение, если цена не указана -->
                @if(!$product->price)
                <div class="alert alert-warning mt-3 mb-0" role="alert">
                    Цена временно недоступна. Уточните у менеджера.
                </div>
                @endif
            </div>
        </div>
    </x-slot:mainContent>
</x-layout.app>