@extends("auth.layouts.app")

@section("content")

<!-- component -->
<section class="py-26 bg-white dark:bg-gray-900">
    <div class="container px-4 mx-auto">
        <div class="max-w-md mx-auto py-8">

            <form method="POST" action="{{ route('admin.login') }}"
                class="bg-white shadow border-2 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg p-6"> <!-- Added border -->
                @csrf
                <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-2 text-gray-900 dark:text-white">Sign in</h2>
            </div>
                <div class="mb-6">
                    <label class="block mb-2 font-extrabold text-gray-900 dark:text-white" for="email">Email</label>
                    <input
                    name="email"
                        class="inline-block w-full p-4 leading-6 text-lg font-extrabold placeholder-blue-900 bg-white shadow border-2 border-blue-900 rounded dark:bg-gray-700 dark:border-gray-600"
                        type="email" placeholder="email" required value="{{ old('email') }}">
                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                        @error('email')
                            {{$message}}
                        @enderror
                    </p>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 font-extrabold text-gray-900 dark:text-white" for="password">Password</label>
                    <input
                        name="password"
                        class="inline-block w-full p-4 leading-6 text-lg font-extrabold placeholder-blue-900 bg-white shadow border-2 border-blue-900 rounded dark:bg-gray-700 dark:border-gray-600"
                        type="password" placeholder="**********" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                        @error('password')
                            {{$message}}
                        @enderror
                    </p>
                </div>
                <div class="flex flex-wrap -mx-4 mb-6 items-center justify-between">
                    <div class="w-full lg:w-auto px-4 mb-4 lg:mb-0">
                        <label for="remember">
                            <input type="checkbox">
                            <span class="ml-1 font-extrabold text-gray-900 dark:text-white">Remember me</span>
                        </label>
                    </div>
                    <div class="w-full lg:w-auto px-4"><a class="inline-block font-extrabold hover:underline text-blue-800 dark:text-blue-400"
                            href="#">Forgot your password?</a></div>
                </div>
                <button
                    class="inline-block w-full py-4 px-6 mb-6 text-center text-lg leading-6 text-white font-extrabold bg-blue-800 hover:bg-blue-900 border-3 border-blue-900 shadow rounded transition duration-200 dark:bg-blue-600 dark:hover:bg-blue-700">Sign
                    in</button>
                </form>
        </div>
    </div>
</section>


@endsection
