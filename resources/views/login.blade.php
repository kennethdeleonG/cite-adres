<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    {{-- NAVBAR --}}
    <nav class="bg-[#531717] border-gray-200 dark:bg-gray-900 h-16">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="/">
                <div class="flex items-center">
                    <img src="{{ asset('images/cite-logo.png') }}" class="h-8 mr-3" alt="CITE logo" />
                    <span
                        class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white text-[#E5B040]">CITE
                        ADRES: A
                        Document Repository System</span>
                </div>
            </a>
        </div>
    </nav>

    {{-- HERO 1 --}}
    <div class="w-full min-h-[calc(100vh-4rem)] relative ">
        <div class="flex justify-between min-h-[calc(100vh-4rem)] ">
            <div class="w-1/2  py-10 pl-6 relative "
                style="
                background-image: url('{{ asset('images/bustos.jpg') }}');
                background-repeat: no-repeat;
                background-size: cover;
                ">

                <div class="mt-8 absolute bottom-0 left-0 p-8 ">

                    <div class="bg-[#531717] p-8">
                        <p class="text-md font-bold text-white">
                            CITE ADRES: A Document Repository System
                        </p>
                        <p class="text-sm text-white">
                            for BSU Bustos Campus. Manage and access important documents
                            for your coursework and academic needs with our user-friendly
                            interface and easy search options. Find course syllabi, lecture notes,
                            research papers, and more quickly with our advanced filtering options based
                            on keywords, topics, or file types. Improve your document management
                            process and access important resources easily with CITE ADRESS.
                        </p>
                    </div>
                </div>
            </div>
            <div class="w-1/2 py-10  flex items-center  flex-col px-4">

                <img src="{{ asset('images/bulsu-logo.png') }}" class="h-32 ml-4 mb-6" alt="Bulsu logo" />

                <div class="mx-8 p-6 bg-white border border-gray-200 rounded-lg shadow w-full">
                    <h5 class="text-2xl font-bold tracking-tight text-gray-700 dark:text-white mb-4 text-center">
                        Login
                    </h5>

                    @if (session('flash_success'))
                        <div class="alert alert-success">
                            <div class="bg-green-300 text-sm mt-2 text-center py-1 mb-4">
                                {{ session('flash_success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <div class="bg-red-300 text-sm mt-2 text-center py-1 mb-4">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <x-forms.post :action="route('login.store')">
                        <div class="mb-4 ">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                address</label>
                            <input type="email" id="email" name="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="john.doe@bulsu.edu.ph" required value="{{ old('email') }}">
                            @error('email')
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" id="password" name="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="•••••••••" required value="{{ old('password') }}">
                            @error('password')
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                            @enderror
                        </div>


                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none
                             focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 
                             text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
                    </x-forms.post>
                </div>

                <h5 class="text-md tracking-tight text-gray-700 dark:text-white mb-4 text-center mt-4">
                    Don't have an account ? <a href="/register" class="text-blue-500 underline">Register</a>
                </h5>
            </div>
        </div>
    </div>

</body>

</html>
