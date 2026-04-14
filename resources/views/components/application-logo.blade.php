<head>
    <style>
        .banner-img {
    width: 100%;            
    height: 220px;            
    object-fit: cover;      
    display: block;           

    border-radius: 10px;      /
    box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
}
</style>
</head>
<body>
    <img src="{{ asset('images/banner.png') }}"
     alt="Banner"
     class="banner-img"
     {{ $attributes }}> 
</body>