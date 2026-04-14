<x-guest-layout>

<style>
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('{{ asset('images/banner.png') }}') no-repeat center;
    background-size: cover;
    position: relative;
}

.login-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
}

.login-box {
    position: relative;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    width: 380px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    z-index: 2;
}

.brand {
    text-align: center;
    margin-bottom: 20px;
}

.brand img {
    width: 70px;
}

.brand h2 {
    margin: 10px 0 0;
    font-weight: bold;
    color: #333;
}

.brand p {
    font-size: 13px;
    color: gray;
}
</style>

<div class="login-wrapper">

    <div class="login-overlay"></div>

    <div class="login-box">

        <!-- BRAND -->
        <div class="brand">
            <img src="{{asset('images/banner.png')}}" width="1000px">
            <h2>ShopLaptop</h2>
            <p>Đăng nhập để tiếp tục</p>
        </div>

        <!-- STATUS -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <label>Email</label>
                <input type="email" name="email"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập email"
                    value="{{ old('email') }}"
                    required>
            </div>

            <!-- Password -->
            <div class="mt-3">
                <label>Mật khẩu</label>
                <input type="password" name="password"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập mật khẩu"
                    required>
            </div>

            <!-- Remember -->
            <div class="mt-3">
                <label>
                    <input type="checkbox" name="remember">
                    Ghi nhớ đăng nhập
                </label>
            </div>

            <!-- BUTTON -->
            <button class="mt-4 w-full bg-blue-600 text-white p-2 rounded">
                Đăng nhập
            </button>

            <!-- FOOTER -->
            <div class="text-center mt-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>

        </form>

    </div>

</div>

</x-guest-layout>