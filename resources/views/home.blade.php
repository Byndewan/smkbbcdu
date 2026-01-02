<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('output.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" href="{{ asset($setting->favicon ?? '') }}">
    <title>BBC Pay</title>
</head>

<body class="bg-[#EDF2F4] min-h-screen">

    <!-- Preloaderr -->
    <div id="preloader"
        class="fixed inset-0 bg-white z-[9999] flex flex-col gap-5 items-center justify-center transition-opacity duration-300">
        <div class="w-12 h-12 border-4 border-red-600 border-t-transparent rounded-full animate-spin"></div>
        <p class="font-bold">Memuat halaman...</p>
    </div>

    <!-- Navbar Section-->
    <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="{{ $setting->logo ?? '' }}" class="h-10 w-auto max-w-[160px]" alt="Logo">
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-gray-700 font-medium">
                    <a href="#featureSection"
                        class="hover:text-red-600 transition-colors duration-200 py-2 mr-4">Kelebihan</a>
                    <a href="#howItWorksSection"
                        class="hover:text-red-600 transition-colors duration-200 py-2 mr-4">Panduan Siswa</a>
                    <a href="#faqSection" class="hover:text-red-600 transition-colors duration-200 py-2">FAQ</a>
                </nav>

                <!-- Desktop Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="#howItWorksSection"
                        class="px-4 py-2 border border-red-600 text-red-600 rounded-full text-sm hover:bg-red-50 transition-colors duration-200 flex items-center">
                        <i class="fas fa-book mr-2"></i> Panduan
                    </a>
                    @guest('student')
                        <a href="{{ route('login') }}"
                            class="px-6 py-2 bg-red-600 text-white rounded-full text-sm hover:bg-red-700 shadow-md transition-colors duration-200">
                            Login
                        </a>
                    @endguest
                    @auth('student')
                        <a href="{{ route('student.dashboard') }}"
                            class="px-6 py-2 bg-red-600 text-white rounded-full text-sm hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-house mr-2"></i> Dashboard
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="text-gray-700 focus:outline-none md:hidden p-2" id="hamburgerBtn"
                    aria-label="Toggle menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar"
        class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden overflow-y-auto">
        <div class="p-4 flex items-center justify-between bg-white sticky top-0">
            <div class="flex items-center">
                <img src="{{ $setting->logo ?? '' }}" class="h-10 w-auto max-w-[160px]" alt="Logo">
            </div>
            <button id="closeSidebar" class="text-gray-500 hover:text-red-600 p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 flex flex-col">
            <a href="#featureSection"
                class="py-3 px-4 hover:bg-red-50 hover:text-red-600 rounded-full transition-colors duration-200"
                onclick="closeSidebar()">Kelebihan</a>
            <a href="#howItWorksSection"
                class="py-3 px-4 hover:bg-red-50 hover:text-red-600 rounded-full transition-colors duration-200"
                onclick="closeSidebar()">Panduan Siswa</a>
            <a href="#faqSection"
                class="py-3 px-4 hover:bg-red-50 hover:text-red-600 rounded-full transition-colors duration-200"
                onclick="closeSidebar()">FAQ</a>
        </div>
        <div class="p-4 mt-0 sticky bottom-0 bg-white">
            @guest('student')
                <a href="{{ route('login') }}"
                    class="block w-full text-center font-bold px-6 mb-1 py-3 bg-red-600 text-white rounded-full text-sm hover:bg-red-700 shadow-md transition-colors duration-200">
                    Login
                </a>
            @endguest
            @auth('student')
                <a href="{{ route('student.dashboard') }}"
                    class="block w-full text-center px-4 py-3 bg-red-600 text-white rounded-full text-sm hover:bg-red-700 transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-house mr-2"></i> Dashboard
                </a>
            @endauth
        </div>
    </div>

    <!-- Overlayy -->
    <div id="overlay"
        class="fixed inset-0 bg-white bg-opacity-10 hidden z-40 md:hidden transition-opacity duration-500"></div>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16 md:py-24" id="heroSection">
        <!-- Background Elements -->
        <div>
            <!-- Floating circles -->
            <div class="absolute w-64 h-64 bg-gradient-to-r from-red-100 to-red-200 rounded-full opacity-10 top-1/4 left-1/4 animate-[floating_8s_ease_in_out_infinite]"
                style="animation-delay: 1s;"></div>
            <div class="absolute w-40 h-40 bg-red-200 rounded-full opacity-20 top-1/3 right-1/4 animate-[floating_8s_ease_in_out_infinite]"
                style="animation-delay: 2s;"></div>

            <!-- animate-Floating blobs -->
            <div class="absolute w-80 h-80 bg-gradient-to-br from-red-100 to-red-200 opacity-10 rounded-[50%] top-1/2 left-10 animate rotate-0 animate-[floating_8s_ease_in_out_infinite]"
                style="animation-delay: 0.5s;"></div>
            <div class="absolute w-60 h-60 bg-gradient-to-br from-red-200 to-red-300 opacity-10 rounded-[40%] bottom-20 right-20 rotate-90 animate-[floating_8s_ease_in_out_infinite]"
                style="animation-delay: 3s;"></div>

            <!-- Decorative shapes -->
            <div class="absolute w-32 h-32 bg-red-100 rounded-full opacity-10 top-24 left-10 animate-pulse"></div>
            <div class="absolute w-24 h-24 bg-red-200 rounded-full opacity-10 bottom-32 right-32 animate-pulse"
                style="animation-delay: 1s;"></div>
            <div class="absolute w-16 max-h-16 bg-red-300 rounded-full opacity-10 top-40 right-40 animate-pulse"
                style="animation-delay: 2s;"></div>

            <!-- animate-Floating triangles -->
            <div
                class="absolute w-0 h-0 border-l-[40px] border-r-[40px] border-b-[70px] border-b-red-200 border-l-transparent border-r-transparent opacity-10 top-20 right-40 animate-[floating_8s_ease_in_out_infinite]">
            </div>
            <div
                class="absolute w-0 h-0 border-l-[30px] border-r-[30px] border-b-[50px] border-b-red-300 border-l-transparent border-r-transparent opacity-10 bottom-40 left-40 animate-[floating_8s_ease_in_out_infinite]">
            </div>
        </div>
        <!-- Hero Section Main Content -->
        <div class="container mx-auto px-4 md:px-8">
            <div class="max-w-3xl mx-auto text-center relative z-10">
                <!-- Hero Section Teks -->
                <span
                    class="inline-block px-6 py-2 text-sm bg-gradient-to-r from-red-100 to-red-200 text-red-600 rounded-full mb-6 font-medium shadow-sm">
                    <i class="{{ $setting->hero_icon_title ?? '' }} mr-2"></i>{{ $setting->hero_title ?? '' }}
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-gray-700 mb-6 leading-tight">
                    {{ $setting->hero_heading ?? '' }}
                </h1>
                <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                    {!! $setting->hero_sort_desc ?? '' !!}
                </p>
                <!-- Hero Section Button -->
                @if (
                    !empty(
                        $setting->hero_button_link ||
                            $setting->hero_button_name ||
                            $setting->hero_button_link2 ||
                            $setting->hero_button_name2
                    ))
                    <div class="flex flex-col sm:flex-row justify-center gap-4 md:px-16">
                        @guest('student')
                            @if (!empty($setting->hero_button_link || $setting->hero_button_name))
                                <a href="{{ $setting->hero_button_link }}"
                                    class="px-6 py-3 sm:mt-0 mt-8 bg-red-600 text-white rounded-full font-medium hover:bg-red-700 transition shadow-lg">
                                    {{ $setting->hero_button_name }}
                                </a>
                            @endif
                        @endguest
                        @auth('student')
                            <a href="{{ route('student.dashboard') }}"
                                class="px-6 py-3 sm:mt-0 mt-8 bg-red-600 text-white rounded-full font-medium hover:bg-red-700 transition shadow-lg">
                                Lihat Dashboard
                            </a>
                        @endauth
                        @if (!empty($setting->hero_button_link2 || $setting->hero_button_name2))
                            <a href="#howItWorksSection"
                                class="px-6 py-3 mt-8 border border-red-600 text-red-600 rounded-full font-medium hover:bg-red-600 hover:text-white transition">
                                <i class="fas fa-book mr-2"></i> {{ $setting->hero_button_name2 }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            <!-- Hero Section Counter -->
            <div class="mt-16 flex flex-wrap justify-center gap-8 md:gap-16">
                <div class="text-center">
                    <div class="flex items-center justify-center text-3xl md:text-4xl font-bold text-red-600 mb-2 ">
                        <div class="counter" data-target="{{ $majorCount }}">0</div>
                        <span class="text-sm">-/+</span>
                    </div>
                    <div class="text-gray-600">Jurusan</div>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center text-3xl md:text-4xl font-bold text-red-600 mb-2 ">
                        <div class="counter" data-target="{{ $studentCount }}">0</div>
                        <span class="text-sm">-/+</span>
                    </div>
                    <div class="text-gray-600">Siswa/i</div>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center text-3xl md:text-4xl font-bold text-red-600 mb-2 ">
                        <div class="counter" data-target="99.9">0</div>
                        <span class="text-sm">%</span>
                    </div>
                    <div class="text-gray-600">Keamanan Data</div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Feature Section -->
    <section class="relative overflow-hidden pt-16 md:pt-24 bg-white" id="featureSection">
        <!-- Feature Section Heading -->
        <div class="relative z-10 max-w-3xl mx-auto px-4">
            <div class="text-center mb-4">
                <span class="inline-block bg-red-100 text-red-600 rounded-full px-6 py-2 font-medium mb-4">
                    {{ $setting->features_title }}
                </span>
            </div>
            <h3 class="text-center font-bold text-3xl md:text-4xl text-gray-800 mb-6">{{ $setting->features_heading }}
            </h3>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-16">
                {{ $setting->features_sub_heading }}
            </p>
        </div>
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <!-- Floating blobs -->
            <div
                class="absolute w-80 h-80 rounded-full bg-gradient-to-r from-red-100 to-red-200 opacity-10 top-20 left-10 blur-3xl">
            </div>
            <div class="absolute w-64 h-64 rounded-full bg-red-100 opacity-10 top-1/4 right-10 blur-3xl"
                style="animation-delay: 2s;"></div>
            <div class="absolute w-40 h-40 rounded-full bg-red-200 opacity-10 bottom-20 left-1/4 blur-3xl"
                style="animation-delay: 4s;"></div>

            <!-- Floating circles -->
            <div class="absolute w-12 h-12 rounded-full bg-red-300 opacity-20 top-1/3 left-1/4"></div>
            <div class="absolute w-8 h-8 rounded-full bg-red-400 opacity-20 top-1/2 right-1/3"></div>
            <div class="absolute w-6 h-6 rounded-full bg-red-500 opacity-20 bottom-1/4 left-2/3"></div>
        </div>

        <!-- Feature Section Main Content -->
        <div class="flex flex-wrap md:flex-row items-center justify-center gap-10 my-24 px-10">
            <!-- Feature Card 1 -->
            @foreach ($features as $item)
                <div class="flex flex-col shrink w-md h-100 items-center justify-center hover:-translate-y-1.5"
                    data-aos="fade-right" data-aos-anchor-placement="top-center" style="transition: all 0.3s;">
                    <!-- Feature Image 1 -->
                    <img src="{{ asset($item->features_image) }}" alt="Feature Image 1" class="w-xs h-60">
                    <!-- Feature Teks 1 -->
                    <div class="my-6 text-center text-wrap">
                        <h6 class="text-xl font-semibold">{{ $item->features_card_heading }}</h6>
                        <p class="text-gray-900 py-2 text-md">{!! $item->features_card_sort_desc !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="relative py-16 md:py-24 overflow-hidden" id="howItWorksSection">
        <!-- How It Works Background elements -->
        <div class="absolute top-0 left-0 w-full h-full wave-pattern opacity-10 z-0"></div>
        <div class="absolute -top-32 -right-32 w-64 h-64 rounded-full bg-red-100 opacity-20 blur-3xl z-0"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-red-200 opacity-10 blur-3xl z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- How It Works Section Heading -->
            <div class="text-center mb-16 md:mb-20">
                <div class="inline-block bg-red-100 text-red-600 rounded-full px-6 py-2 mb-4">
                    <span class="font-medium">{{ $setting->how_title }}</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $setting->how_heading }}</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $setting->how_sub_heading }}
                </p>
            </div>

            <!--  How It Works SectionMain Content -->
            <div class="flex flex-col lg:flex-row items-center gap-10 xl:gap-16">

                <!-- How It Works Section Illustration -->
                <div class="w-full lg:w-1/2 flex justify-center mt-8 lg:mt-0 transition" data-aos="fade-up">
                    <div class="relative max-w-lg">
                        <div class="rounded-2xl p-6 shadow-xl">
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg p-2">
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <img src="{{ asset($setting->how_image) }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- How It Works Section Decorative elements -->
                        <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full bg-red-200 opacity-30 z-0"></div>
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-red-100 opacity-40 z-0">
                        </div>
                        <div
                            class="absolute top-1/4 -right-8 w-16 max-h-16 rounded-full bg-red-600 opacity-20 rotate-45 z-0">
                        </div>
                    </div>
                </div>

                <!-- How It Works Section Steps -->
                <div class="w-full lg:w-1/2 space-y-2 relative">
                    <!-- How It Works Section Step 1 -->
                    @foreach ($how_it_works as $item)
                        <div class="relative pl-4 transition" data-aos="fade-up">
                            <div
                                class="step-content flex items-start gap-5 group hover:bg-red-200 hover:scale-105 transition px-5 py-4 rounded-xl">
                                <div
                                    class="step-icon w-14 h-14 rounded-full  bg-red-600 flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                        <i class="{{ $item->how_icon }} text-red-600"></i>
                                        {{ $item->how_item_heading }}
                                    </h4>
                                    <p class="text-gray-600">
                                        {!! $item->how_item_sort_desc !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- How it Works Section Step 7 -->
                    {{-- @guest('student')
                        <div class="relative pl-4 transition" data-aos="fade-up">
                            <div
                                class="step-content flex items-start gap-5 group hover:bg-red-200 hover:scale-105 transition px-5 py-4 rounded-xl">
                                <div
                                    class="step-icon w-14 h-14 rounded-full  bg-red-600 flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
                                    8
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
                                        <!-- <i class="fas fa-door-open "></i> -->
                                        <i class="fa-solid fa-arrow-right-to-bracket text-red-600"></i>
                                        <!-- <i class="fa-regular fa-arrow-right-to-arc"></i> -->
                                        Login Sekarang
                                    </h4>
                                    <a href="{{ route('login.siswa') }}"
                                        class="px-4 py-2 bg-red-600 text-white rounded-full text-sm hover:bg-red-900 hover:text-white shadow-lg transition">
                                        Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endguest --}}

                </div>
            </div>

        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-white py-24 px-12 md:px-16 relative" id="faqSection">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-start">
            <!--FAQ Section Teks -->
            <div class="pr-0 md:pr-10">
                <div class="inline-block bg-red-100 text-red-600 rounded-full px-6 py-2 mb-6 font-medium">
                    <i class="{{ $setting->faq_icon_title }} mr-2"></i>{{ $setting->faq_title }}
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $setting->faq_heading }}
                </h2>
                <p class="text-gray-600 mb-8 max-w-full">
                    {{ $setting->faq_sub_heading }}
                </p>

                {{-- @if (
                    !empty(
                        $setting->faq_button_icon ||
                            $setting->faq_button_name ||
                            $setting->faq_button_icon2 ||
                            $setting->faq_button_name2
                    ))
                    <div class="flex flex-wrap gap-4">
                        @if (!empty($setting->faq_button_icon || $setting->faq_button_name))
                            <a href="{{ $setting->faq_button_link }}" target="_blank"
                                class="contact-btn px-6 py-3 bg-red-600 to-secondary text-white rounded-full font-medium flex items-center cursor-pointer">
                                <i class="{{ $setting->faq_button_icon }} mr-2"></i> {{ $setting->faq_button_name }}
                            </a>
                        @endif
                        @if (!empty($setting->faq_button_icon2 || $setting->faq_button_name2))
                            <a href="{{ $setting->faq_button_link2 }}"
                                class="px-6 py-3 border border-red-600 text-red-600 rounded-full font-medium hover:bg-red-600 hover:text-white transition flex items-center cursor-pointer">
                                <i class="{{ $setting->faq_button_icon2 }} mr-2"></i>
                                {{ $setting->faq_button_name2 }}
                            </a>
                        @endif
                    </div>
                @endif --}}

                <div class="mt-10 p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-100">
                    <div class="flex items-start">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="{{ $setting->faq_tips_icon }} text-primary text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $setting->faq_tips_heading }}</h4>
                            <p class="text-gray-600 text-sm">
                                {!! $setting->faq_tips_description !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section Accordion -->
            <div class="space-y-4">

                <!-- FAQ Item 1 Active -->
                @foreach ($faqs as $item)
                    <div
                        class="faq-item border-y-1 border-y-gray-200 bg-white transition rounded-md shadow-sm overflow-hidden py-4">
                        <button
                            class="w-full flex items-center justify-between px-5 py-4 text-left text-lg font-semibold transition text-gray-900 faq-toggle">
                            <span>{{ $item->faq_card_question }}</span>
                            <span class="faq-icon text-2xl">+</span>
                        </button>
                        <div class="faq-content overflow-hidden transition-all duration-300 ease-in-out"
                            style="max-height: 0; transition: max-height 0.4s ease;">
                            <div class="text-gray-600 px-6 pb-5">
                                <span class="text-md">{!! $item->faq_card_answer !!}</span>
                                <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-100 text-sm">
                                    <i class="{{ $item->faq_card_icon }} mr-2 text-red-600"></i>
                                    {{ $item->faq_card_title }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#2B2D42] text-gray-300 py-12">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ $setting->logo }}" class="h-10 w-20" alt="">
                        {{-- <div class="bg-red-600 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-xl">$</span>
                        </div>
                        <h3 class="text-white text-xl font-bold">BayarSekolah</h3> --}}
                    </div>
                    <p class="mb-4">{!! $setting->footer_description !!}</p>
                </div>

                <!-- About -->
                <div>
                    <h4 class="text-white text-lg font-semibold mb-4">Tentang Aplikasi</h4>
                    <ul class="space-y-2">
                        <li><a href="#featureSection" class="hover:text-white transition">Kelebihan</a></li>
                        <li><a href="#howItWorks" class="hover:text-white transition">Panduan Siswa</a></li>
                        <li><a href="#faqSection" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Policies -->
                <div>
                    <h4 class="text-white text-lg font-semibold mb-4">Kebijakan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                @if (isset($setting->footer_sosmed_name) && !empty($setting->footer_sosmed_name))
                    <div>
                        <h4 class="text-white text-lg font-semibold mb-4">Sosial Media</h4>
                        <ul class="space-y-2">
                            @if (!empty($setting->footer_sosmed_name || $setting->footer_sosmed_icon))
                                <a href="{{ $setting->footer_sosmed_name }}" target="_blank"
                                    class="text-gray-300 hover:text-white transition-all mr-2">
                                    <i class="{{ $setting->footer_sosmed_icon }} text-2xl"></i>
                                </a>
                            @endif
                            {{-- @if (!empty($setting->footer_sosmed_name2 || $setting->footer_sosmed_icon2))
                                <a href="{{ $setting->footer_sosmed_name2 }}" target="_blank"
                                    class="text-gray-300 hover:text-white transition-all mr-2">
                                    <i class="{{ $setting->footer_sosmed_icon2 }} text-2xl"></i>
                                </a>
                            @endif
                            @if (!empty($setting->footer_sosmed_name3 || $setting->footer_sosmed_icon3))
                                <a href="{{ $setting->footer_sosmed_name3 }}" target="_blank"
                                    class="text-gray-300 hover:text-white transition-all mr-2">
                                    <i class="{{ $setting->footer_sosmed_icon3 }} text-2xl"></i>
                                </a>
                            @endif
                            @if (!empty($setting->footer_sosmed_name4 || $setting->footer_sosmed_icon4))
                                <a href="{{ $setting->footer_sosmed_name4 }}" target="_blank"
                                    class="text-gray-300 hover:text-white transition-all mr-2">
                                    <i class="{{ $setting->footer_sosmed_icon4 }} text-2xl"></i>
                                </a>
                            @endif
                            @if (!empty($setting->footer_sosmed_name5 || $setting->footer_sosmed_icon5))
                                <a href="{{ $setting->footer_sosmed_name5 }}" target="_blank"
                                    class="text-gray-300 hover:text-white transition-all mr-2">
                                    <i class="{{ $setting->footer_sosmed_icon5 }} text-2xl"></i>
                                </a>
                            @endif --}}
                        </ul>
                    </div>
                @endif

            </div>

            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p>{!! $setting->footer_copyright !!}</p>
            </div>
        </div>
    </footer>

    <!-- Scrool To Top -->
    <div class="max-h-dvw fixed bottom-6 right-6 z-50 hidden" id="scrollToTopBtn">
        <button
            class="w-14 h-14 bg-red-600 text-white rounded-full flex items-center justify-center text-2xl shadow-lg hover:bg-red-900 transition">
            <i class="fa-solid fa-arrow-up"></i>
        </button>
    </div>

    <!-- Floating WhatsApp -->
    <div class="fixed bottom-6 right-6 z-50 transition-all duration-300" id="whatsappBtn">
        <a href="https://wa.me/62{{ preg_replace('/[^0-9]/', '', str_replace('08', '628', $setting->phone)) }}"
            target="_blank"
            class="w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center text-2xl shadow-lg hover:bg-green-600 transition animate-bounce">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
