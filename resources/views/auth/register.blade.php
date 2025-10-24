<x-guest-layout>
    <!-- <div class="flex flex-col items-center justify-center w-full text-center px-4 py-8 md:py-10 bg-[#E8DBC8] min-h-screen relative overflow-hidden"> -->

    <!-- ===== الزخرفة السفلية ===== -->
    <!-- <div class="absolute bottom-0 left-0 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 250" class="w-full h-24 md:h-28">
                <path fill="#F46A06" d="M0,160L48,144C96,128,192,96,288,117.3C384,139,480,213,576,218.7C672,224,768,160,864,122.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                <path fill="#1F1C1C" d="M0,224L48,218.7C96,213,192,203,288,192C384,181,480,171,576,186.7C672,203,768,245,864,224C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div> -->

    <!-- ===== العنوان ===== -->
    <h1 class="text-[32px] sm:text-[40px] md:text-[48px] font-['Lexend_Deca'] text-[#1F1C1C] leading-tight mb-2 z-10">
        Create account
    </h1>

    <p class="text-[#1F1C1C] text-[14px] sm:text-[16px] md:text-[17px] font-['Lexend_Deca'] mb-6 max-w-md z-10">
        Hello User, thank you for choosing this journey with us. Let’s create your account first.
    </p>

    <!-- ===== نموذج التسجيل ===== -->
    <form method="POST" action="{{ route('register') }}" class="w-full max-w-md bg-transparent z-10">
        @csrf

          <!-- Google Sign Up Button -->
                <a href="{{ route('auth.google') }}"
                   class="w-full flex items-center justify-center gap-3 h-12 bg-white border-2 border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-[#F46A06] transition-all duration-300 mb-6 group">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-[#1F1C1C] font-medium group-hover:text-[#F46A06] transition-colors">Sign up with Google</span>
                </a>
                
        <!-- Full Name -->
        <div class="mb-4 text-left">
            <label for="name" class="block text-[#1F1C1C] text-[15px] mb-1">
                FullNames
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full h-[50px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Phone -->
        <div class="mb-4 text-left">
            <label for="phone" class="block text-[#1F1C1C] text-[15px] mb-1">
                Phone number
            </label>
            <div class="flex items-center">
                <div class="flex items-center bg-white rounded-l-[10px] border-r border-gray-300 px-3 h-[50px]">
                    <span class="text-[#F46A06] text-[16px] font-semibold">+250</span>
                </div>
                <input id="phone" type="text" name="phone"
                    class="w-full h-[50px] rounded-r-[10px] bg-white px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                    placeholder="Enter remaining numbers">
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- Email -->
        <div class="mb-4 text-left">
            <label for="email" class="block text-[#1F1C1C] text-[15px] mb-1">
                Email-address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full h-[50px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                placeholder="example@gmail.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-6 text-left">
            <label for="password" class="block text-[#1F1C1C] text-[15px] mb-1">
                Password
            </label>
            <input id="password" type="password" name="password" required
                class="w-full h-[50px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                placeholder="********">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="mb-4 text-left">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                class="w-full h-[50px] rounded-[10px] bg-white px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F46A06]"
                placeholder="********">
        </div>
        <!-- Sign Up Button -->
        <button type="submit"
            class="w-full h-[45px] bg-[#F46A06] rounded-[10px] shadow text-white text-[15px] font-semibold hover:bg-[#e65f05] transition duration-200">
            Sign Up
        </button>
    </form>
    </div>
</x-guest-layout>
