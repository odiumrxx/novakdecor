@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
	<div class="main-content-wrap">
		<div class="flex items-center flex-wrap justify-between gap20 mb-27">
			<h3>Category Information</h3> {{-- Изменено с "Brand infomation" на "Category Information" --}}
			<ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
				<li>
					<a href="{{route('admin.index')}}">
						<div class="text-tiny">Dashboard</div>
					</a>
				</li>
				<li>
					<i class="icon-chevron-right"></i>
				</li>
				<li>
					<a href="{{route('admin.categories')}}">
						<div class="text-tiny">Категории</div>
					</a>
				</li>
				<li>
					<i class="icon-chevron-right"></i>
				</li>
				<li>
					<div class="text-tiny">Изменить Категорию</div>
				</li>
			</ul>
		</div>
		<!-- edit-category --> {{-- Изменен комментарий с new-category на edit-category --}}
		<div class="wg-box">
			<form class="form-new-product form-style-1" action="{{route('admin.category.update')}}" method="POST"
				enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" name="id" value="{{$category->id}}" />
				<fieldset class="name">
					<div class="body-title">Название категории <span class="tf-color-1">*</span></div>
					<input class="flex-grow" type="text" placeholder="Category name" name="name" tabindex="0"
						value="{{old('name', $category->name)}}" aria-required="true" required=""> {{-- Добавлено old() для значения --}}
				</fieldset>
				@error('name')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

				<fieldset class="name">
					<div class="body-title">Алиас категории <span class="tf-color-1">*</span></div>
					<input class="flex-grow" type="text" placeholder="Category slug" name="slug" tabindex="0" {{-- Изменен placeholder --}}
						value="{{old('slug', $category->slug)}}" aria-required="true" required=""> {{-- Добавлено old() для значения --}}
				</fieldset>
				@error('slug')<span class="alert alert-danger text-center">{{$message}}</span>@enderror
				<fieldset>
					<div class="body-title">Upload images <span class="tf-color-1">*</span>
					</div>
					<div class="upload-image flex-grow">
						{{-- Отображаем текущее изображение, если оно есть, и предпросмотр нового --}}
						<div class="item" id="imgpreview" style="{{ old('image') || $category->image ? '' : 'display:none' }}">
							<img src="{{ old('image') ? asset('uploads/categories/' . old('image')) : ( $category->image ? asset('uploads/categories/' . $category->image) : 'https://placehold.co/124x124/E0E0E0/212121?text=Upload+Image' ) }}" class="effect8" alt="Category Image Preview">
						</div>
						<div id="upload-file" class="item up-load">
							<label class="uploadfile" for="myFile">
								<span class="icon">
									<i class="icon-upload-cloud"></i>
								</span>
								<span class="body-text">Drop your images here or select <span class="tf-color">click to
										browse</span></span>
								<input type="file" id="myFile" name="image" accept="image/*">
							</label>
						</div>
					</div>
				</fieldset>
				@error('image')<span class="alert alert-danger text-center">{{$message}}</span>@enderror
				<div class="bot">
					<div></div>
					<button class="tf-button w208" type="submit">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
	$("#myFile").on("change", function(e) {
		const [file] = this.files;
		if (file) {
			$("#imgpreview img").attr('src', URL.createObjectURL(file));
			$("#imgpreview").show();
		}
	});
	// Генерация slug при изменении имени категории
	$("input[name='name']").on("keyup", function() { // Использовать keyup для динамической генерации
		$("input[name='slug']").val(StringToSlug($(this).val()));
	});
});

function StringToSlug(text) {
    // Карта для транслитерации русских символов
    const translitMap = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z', 'и': 'i',
        'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
        'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch', 'ъ': '', 'ы': 'y', 'ь': '',
        'э': 'e', 'ю': 'yu', 'я': 'ya',
        'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh', 'З': 'Z', 'И': 'I',
        'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T',
        'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'Ts', 'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sch', 'Ъ': '', 'Ы': 'Y', 'Ь': '',
        'Э': 'E', 'Ю': 'Yu', 'Я': 'Ya'
    };

    let convertedText = '';
    for (let i = 0; i < text.length; i++) {
        const char = text[i];
        convertedText += translitMap[char] || char; // Используем транслитерацию или оставляем как есть
    }

    return convertedText.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, "") // Удаляем все, кроме латинских букв, цифр, пробелов и дефисов
        .trim() // Обрезаем пробелы по краям
        .replace(/\s+/g, "-"); // Заменяем пробелы на дефисы
}
</script>
@endpush
