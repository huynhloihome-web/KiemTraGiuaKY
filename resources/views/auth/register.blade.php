<x-guest-layout>

<style>
.register-wrapper {
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;

    background: url('{{ asset('images/banner.png') }}') no-repeat center center;
    background-size: cover;
    position: relative;
}

/* overlay */
.register-wrapper::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* card */
.register-box {
    position: relative;
    z-index: 2;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    width: 380px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* brand */
.brand {
    text-align: center;
    margin-bottom: 20px;
}

.brand img {
    width: 70px;
}

.brand h2 {
    margin-top: 10px;
    font-weight: bold;
}

</style>

<div class="register-wrapper">

    <div class="register-box">

        <!-- LOGO -->
        <div class="brand">
            <img src="{{asset('images/banner.png')}}">
            <h2>ShopLaptop</h2>
            <p>Đăng ký tài khoản mới</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label>Họ tên</label>
                <input type="text" name="name"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập họ tên"
                    value="{{ old('name') }}" required>
            </div>

            <!-- Email -->
            <div class="mt-3">
                <label>Email</label>
                <input type="email" name="email"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập email"
                    value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div class="mt-3">
                <label>Mật khẩu</label>
                <input type="password" name="password"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập mật khẩu" required>
            </div>

            <!-- Confirm -->
            <div class="mt-3">
                <label>Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation"
                    class="block w-full border p-2 rounded"
                    placeholder="Nhập lại mật khẩu" required>
            </div>

            <!-- BUTTON -->
            <button class="mt-4 w-full bg-blue-600 text-white p-2 rounded">
                Đăng ký
            </button>

            <!-- LOGIN LINK -->
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-blue-600 text-sm">
                    Đã có tài khoản? Đăng nhập
                </a>
            </div>

        </form>

    </div>

</div>

</x-guest-layout>