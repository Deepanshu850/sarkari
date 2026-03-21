<section class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full border-2 border-gold-400 flex items-center justify-center bg-navy-600">
                <svg class="w-8 h-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-navy-600">Reset Password</h1>
            <p class="text-gray-500 mt-1">Enter your email to receive a reset link</p>
        </div>

        <div class="paper rounded-2xl p-6 md:p-8 paper-shadow">
            <div class="h-1 bg-gradient-to-r from-saffron-500 via-white to-india-500 -mx-6 md:-mx-8 -mt-6 md:-mt-8 rounded-t-2xl mb-6"></div>

            <form method="POST" action="/forgot-password" class="space-y-5">
                <?= csrf_field() ?>
                <div>
                    <label for="email" class="block text-sm font-semibold text-navy-600 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="aapka@email.com">
                </div>
                <button type="submit"
                    class="w-full py-3.5 bg-saffron-500 text-white rounded-lg font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gold-100 text-center">
                <a href="/login" class="text-sm text-saffron-600 font-bold hover:text-saffron-700">Back to Login</a>
            </div>
        </div>
    </div>
</section>
