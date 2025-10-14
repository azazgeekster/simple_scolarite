@props(['disabled' => ''])

<div class="relative z-0 w-full mb-5 group">
    <!-- Input Field -->
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-400 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
        placeholder=" " value="{{ old($name, $value) }}" {{ $disabled!=''? 'disabled':'' }}> 

    <!-- Floating Label -->
    <label for="{{ $name }}"
        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        {{ $label }}
    </label>

    <!-- Error Message -->
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
    @enderror
</div>