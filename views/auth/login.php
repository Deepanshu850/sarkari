<section class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Header with seal -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full border-2 border-gold-400 flex items-center justify-center bg-navy-600">
                <svg class="w-8 h-8 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/>
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-navy-600">Welcome Back</h1>
            <p class="text-gray-500 mt-1">Login to access your blueprints</p>
        </div>

        <div class="paper rounded-2xl p-6 md:p-8 paper-shadow">
            <!-- Tricolor top line -->
            <div class="h-1 bg-gradient-to-r from-saffron-500 via-white to-india-500 -mx-6 md:-mx-8 -mt-6 md:-mt-8 rounded-t-2xl mb-6"></div>

            <form method="POST" action="/login" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label for="email" class="block text-sm font-semibold text-navy-600 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required autofocus
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="aapka@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-navy-600 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="Enter your password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-saffron-500 border-gold-300 rounded focus:ring-saffron-500">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-saffron-600 hover:text-saffron-700 font-medium">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-saffron-500 text-white rounded-lg font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Login
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gold-100 text-center">
                <p class="text-sm text-gray-600">
                    Account nahi hai? <a href="/register" class="text-saffron-600 font-bold hover:text-saffron-700">Register Karein</a>
                </p>
            </div>
        </div>
    </div>
</section>
