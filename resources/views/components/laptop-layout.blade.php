<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>

    <link rel="stylesheet" href="{{asset('library/bootstrap.min.css')}}">
    <script src="{{asset('library/jquery.slim.min.js')}}"></script>
    <script src="{{asset('library/popper.min.js')}}"></script>
    <script src="{{asset('library/bootstrap.bundle.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="{{asset('library/jquery-3.7.1.js')}}"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap4.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap4.css">

    <style>
        /* === PHẦN CSS NGUYÊN BẢN CỦA PROJECT === */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

       .container {
    max-width: 1000px;
    margin: 0 auto;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

        /* === PHẦN CSS CÁC CHỨC NĂNG BẠN MỚI THÊM === */
        .custom-navbar {
            background-color: #122333;
            padding: 10px 0;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #00d4ff !important;
        }

        /* SEARCH */
        .search-bar {
            flex: 1;
            max-width: 400px;
            position: relative;
            margin: 0 20px;
        }

        .search-bar input {
            width: 100%;
            border-radius: 20px;
            border: none;
            padding: 6px 15px;
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 3px;
            border: none;
            background: transparent;
        }

        /* CART */
        .cart-box {
            position: relative;
            margin-left: 15px;
            margin-right: 15px;
        }

        .cart-box i {
            font-size: 22px;
            color: white;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background: #23b85c;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            padding: 2px 6px;
        }

        /* BANNER */
        .banner img {
            width: 100%;
            height: auto;
            display: block;
        }
        
    </style>
</head>

<body>

<header>
    <div class="container">
        <div class="banner">
            <img src="{{asset('images/banner.png')}}" class="img-fluid w-100" style="display: block;" alt="Banner">
        </div>
    </div>

    <div class="container p-0"> 
        <nav class="navbar navbar-expand-lg navbar-dark custom-navbar px-0" style="border-radius: 0 0 4px 4px;">
           <div class="container d-flex align-items-center justify-content-between p-0">

                <ul class="navbar-nav d-flex flex-row" style="white-space: nowrap; overflow-x: auto; scrollbar-width: none;">
                    <li class="nav-item px-2">
                        <a class="nav-link {{ !request('brand') ? 'font-weight-bold text-info' : '' }}" href="{{ route('laptop.home') }}">
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li class="nav-item px-2">
                            <a class="nav-link {{ request('brand') == $cat->id ? 'font-weight-bold text-info' : '' }}" 
                               href="{{ route('laptop.home', ['brand' => $cat->id]) }}">
                                {{ $cat->ten_danh_muc }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <form method="post" action="{{url('/timkiem')}}" class="search-bar">
                    {{ csrf_field() }}
                    <input type="text" name="keyword" placeholder="Tìm kiếm laptop...">
                    <button class="search-btn">
                        <i class="fa fa-search"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center px-2" style="white-space: nowrap;">
                    
                    <div class="cart-box">
                        <a href="{{url('/gio-hang')}}">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-count">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                    </div>

                    <div class="ml-3 d-flex align-items-center">
                        @auth
                            <div class="dropdown">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Quản lý</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Đăng xuất</a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Đăng nhập</a>&nbsp;
                            <a href="{{ route('register') }}" class="btn btn-success btn-sm">Đăng ký</a>
                        @endauth
                    </div>

                </div>

            </div>
        </nav>
    </div>
</header>

<main class="container mt-3">
    {{$slot}}
</main>

</body>
</html>