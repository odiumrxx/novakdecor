// product-add.blade.php
@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Добавить Продукт</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Панель управления</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Продукция</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Добавить продукт</div>
                    </li>
                </ul>
            </div>
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.store') }}">
                @csrf
                <div class="wg-box">
                    {{-- Поле "Название продукта" --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Название продукта <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Введите название продукта" name="name"
                            tabindex="0" value="{{ old('name') }}" aria-required="true" required>
                        <div class="text-tiny">Название продукта не должно превышать 100 символов.</div>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- Поле "Слаг" --}}
                    <fieldset class="name">
                        <div class="body-title mb-10">Слаг <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Введите слаг продукта" name="slug"
                            tabindex="0" value="{{ old('slug') }}" aria-required="true" required>
                        <div class="text-tiny">Это поле будет автоматически сгенерировано из названия продукта.</div>
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="gap22 cols">
                        {{-- Поле "Категория" --}}
                        <fieldset class="category">
                            <div class="body-title mb-10">Категория <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="category_id" required>
                                    <option value="">Выберите категорию</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror

                        {{-- Поле "Бренд" --}}
                        <fieldset class="brand">
                            <div class="body-title mb-10">Бренд <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="brand_id" required>
                                    <option value="">Выберите бренд</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Поле "Краткое описание" --}}
                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Краткое описание <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Краткое описание" tabindex="0"
                            aria-required="true" required>{{ old('short_description') }}</textarea>
                        <div class="text-tiny">Представьте краткий обзор продукта.</div>
                    </fieldset>
                    @error('short_description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- Поле "Описание" --}}
                    <fieldset class="description">
                        <div class="body-title mb-10">Описание <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Описание" tabindex="0" aria-required="true" required>{{ old('description') }}</textarea>
                        <div class="text-tiny">Предоставьте подробное описание продукта.</div>
                    </fieldset>
                    @error('description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="wg-box">
                    {{-- Секция "Загрузить изображения" (главное изображение) --}}
                    <fieldset>
                        <div class="body-title">Загрузить основное изображение <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{ old('image') ? '' : 'display:none' }}">
                                {{-- Исправленный путь для предпросмотра --}}
                                <img src="{{ old('image') ? asset('uploads/products/' . old('image')) : '' }}"
                                    class="effect8" alt="Изображение продукта">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Перетащите изображение сюда или <span class="tf-color">нажмите
                                            для выбора</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- Секция "Загрузить изображения галереи" --}}
                    <fieldset>
                        <div class="body-title mb-10">Загрузить изображения галереи</div>
                        <div class="upload-image mb-16">
                            {{-- Контейнер для предпросмотра изображений галереи --}}
                            <div class="gallery-preview-container flex flex-wrap gap10 mb-10">
                                {{-- Динамически добавленные предпросмотры будут здесь --}}
                                @if (old('images'))
                                    @foreach (old('images') as $imagePath)
                                        <div class="item">
                                            {{-- Исправленный путь для предпросмотра --}}
                                            <img src="{{ asset('uploads/products/' . $imagePath) }}" class="effect8"
                                                alt="Изображение галереи">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Перетащите изображения сюда или <span class="tf-color">нажмите
                                            для выбора</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @if ($errors->has('images.*') || $errors->has('images'))
                        <span
                            class="alert alert-danger text-center">{{ $errors->first('images') ?: $errors->first('images.*') }}</span>
                    @endif


                    <div class="cols gap22">
                        {{-- Поле "Обычная цена" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">Обычная цена <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Введите обычную цену" name="regular_price"
                                tabindex="0" value="{{ old('regular_price') }}" aria-required="true" required>
                        </fieldset>
                        @error('regular_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror

                        {{-- Поле "Цена со скидкой" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">Цена со скидкой</div>
                            <input class="mb-10" type="text" placeholder="Введите цену со скидкой" name="sale_price"
                                tabindex="0" value="{{ old('sale_price') }}" aria-required="false">
                        </fieldset>
                        @error('sale_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        {{-- Поле "SKU" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Введите SKU" name="SKU"
                                tabindex="0" value="{{ old('SKU') }}" aria-required="true" required>
                        </fieldset>
                        @error('SKU')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror

                        {{-- Поле "Количество" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">Количество <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Введите количество" name="quantity"
                                tabindex="0" value="{{ old('quantity') }}" aria-required="true" required>
                        </fieldset>
                        @error('quantity')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        {{-- Поле "Наличие" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">Наличие</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>В
                                        наличии</option>
                                    <option value="outofstock"
                                        {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Нет в наличии</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('stock_status')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror

                        {{-- Поле "Рекомендуемый" --}}
                        <fieldset class="name">
                            <div class="body-title mb-10">Рекомендуемый</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>Нет</option>
                                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Да</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('featured')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Добавить продукт</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Предпросмотр основного изображения
            $("#myFile").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                } else {
                    $("#imgpreview").hide(); // Скрываем, если файл не выбран
                    $("#imgpreview img").attr('src', ''); // Очищаем src
                }
            });

            // Автоматическая генерация слага при вводе названия продукта
            $("input[name='name']").on("keyup", function() {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });

            // Предпросмотр изображений галереи
            $("#gFile").on("change", function(e) {
                const files = this.files;
                const galleryPreviewContainer = $(".gallery-preview-container");
                galleryPreviewContainer.empty(); // Очищаем предыдущие предпросмотры при новом выборе

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        // Создаем контейнер для каждого изображения (по желанию, для стилизации)
                        const itemDiv = $('<div>').addClass('item');
                        const img = $('<img>').attr('src', URL.createObjectURL(file)).addClass('effect8');
                        itemDiv.append(img);
                        galleryPreviewContainer.append(itemDiv);
                    }
                }
            });
        });

        // Функция транслитерации для слага
        function StringToSlug(text) {
            const translitMap = {
                'а': 'a',
                'б': 'b',
                'в': 'v',
                'г': 'g',
                'д': 'd',
                'е': 'e',
                'ё': 'yo',
                'ж': 'zh',
                'з': 'z',
                'и': 'i',
                'й': 'y',
                'к': 'k',
                'л': 'l',
                'м': 'm',
                'н': 'n',
                'о': 'o',
                'п': 'p',
                'р': 'r',
                'с': 's',
                'т': 't',
                'у': 'u',
                'ф': 'f',
                'х': 'h',
                'ц': 'ts',
                'ч': 'ch',
                'ш': 'sh',
                'щ': 'sch',
                'ъ': '',
                'ы': 'y',
                'ь': '',
                'э': 'e',
                'ю': 'yu',
                'я': 'ya',
                'А': 'A',
                'Б': 'B',
                'В': 'V',
                'Г': 'G',
                'Д': 'D',
                'Е': 'E',
                'Ё': 'Yo',
                'Ж': 'Zh',
                'З': 'Z',
                'И': 'I',
                'Й': 'Y',
                'К': 'K',
                'Л': 'L',
                'М': 'M',
                'Н': 'N',
                'О': 'O',
                'П': 'P',
                'Р': 'R',
                'С': 'S',
                'Т': 'T',
                'У': 'U',
                'Ф': 'F',
                'Х': 'H',
                'Ц': 'Ts',
                'Ч': 'Ch',
                'Ш': 'Sh',
                'Щ': 'Sch',
                'Ъ': '',
                'Ы': 'Y',
                'Ь': '',
                'Э': 'E',
                'Ю': 'Yu',
                'Я': 'Ya'
            };

            let convertedText = '';
            for (let i = 0; i < text.length; i++) {
                const char = text[i];
                convertedText += translitMap[char] || char;
            }

            return convertedText.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, "") // Удаляем все, кроме латинских букв, цифр, пробелов и дефисов
                .trim() // Удаляем пробелы по краям
                .replace(/\s+/g, "-"); // Заменяем пробелы на дефисы
        }
    </script>
@endpush
