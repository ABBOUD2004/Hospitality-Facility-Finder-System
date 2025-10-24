<x-guest-layout>
    <div class="flex flex-col items-center justify-center w-full text-center px-6 py-10 md:py-20">



        <!-- العنوان -->
        <h1 class="text-[70px] sm:text-[90px] md:text-[100px] font-['Lexend_Deca'] text-[#1F1C1C] leading-none mb-4">
            Sign in
        </h1>

        <p class="text-[#1F1C1C] text-[18px] sm:text-[20px] font-['Lexend_Deca'] mb-10 max-w-2xl">
            Sign into your account to be able to make operations on the system
        </p>

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}" class="w-full max-w-lg bg-transparent">
            @csrf

               <!-- Google Sign In Button -->
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center gap-3 h-12 bg-white border-2 border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-[#F46A06] transition-all duration-300 mb-6 group">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-[#1F1C1C] font-medium group-hover:text-[#F46A06] transition-colors">Sign in with Google</span>
                </a>

            <!-- Email -->
            <div class="mb-6 text-left">
                <label for="email" class="block text-[#1F1C1C] text-[20px] sm:text-[22px] font-['Lexend_Deca'] mb-2">
                    Email-address
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full h-[67px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                    placeholder="example@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4 text-left">
                <label for="password" class="block text-[#1F1C1C] text-[20px] sm:text-[22px] font-['Lexend_Deca'] mb-2">
                    Password
                </label>
                <input id="password" type="password" name="password" required
                    class="w-full h-[67px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                    placeholder="********">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between text-sm mt-2 mb-8 flex-wrap gap-2">
                <label class="flex items-center text-[#1F1C1C] text-[16px] font-['Montserrat']">
                    <input type="checkbox" name="remember"
                        class="w-[18px] h-[17px] border-2 border-[#F46A06] rounded mr-2 accent-[#F46A06]">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-[#F46A06] font-['Montserrat'] hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- زر الدخول -->
            <button type="submit"
                class="w-full h-[45px] bg-[#F46A06] rounded-[10px] shadow-md text-[#1F1C1C]
                       text-[16px] font-['Lexend_Deca'] capitalize hover:bg-[#e65f05] transition duration-200">
                Login
            </button>

            <!-- رابط التسجيل -->
            <p class="text-center mt-6 text-[#555454] font-['Outfit'] text-[16px]">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-[#F46A06] font-semibold hover:underline">
                    Register Now
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
