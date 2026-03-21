<section class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Header with seal -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full border-2 border-gold-400 flex items-center justify-center bg-navy-600">
                <svg class="w-8 h-8 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/>
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-navy-600">Create Account</h1>
            <p class="text-gray-500 mt-1">Apni Sarkari Naukri journey shuru karein</p>
        </div>

        <div class="paper rounded-2xl p-6 md:p-8 paper-shadow">
            <div class="h-1 bg-gradient-to-r from-saffron-500 via-white to-india-500 -mx-6 md:-mx-8 -mt-6 md:-mt-8 rounded-t-2xl mb-6"></div>

            <form method="POST" action="/register" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label for="name" class="block text-sm font-semibold text-navy-600 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= old('name') ?>" required
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="Aapka poora naam">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-navy-600 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="aapka@email.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-navy-600 mb-1.5">Phone Number <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>"
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="9876543210">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-navy-600 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="Minimum 6 characters">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-navy-600 mb-1.5">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-navy-600 placeholder-gray-400"
                        placeholder="Dobara password dalein">
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-saffron-500 text-white rounded-lg font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 flex items-center justify-center gap-2 mt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Register
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gold-100 text-center">
                <p class="text-sm text-gray-600">
                    Pehle se account hai? <a href="/login" class="text-saffron-600 font-bold hover:text-saffron-700">Login Karein</a>
                </p>
            </div>
        </div>
    </div>
</section>
