<style>
.laptop-card {
    border-radius: 10px;
    transition: 0.3s;
    overflow: hidden;
}

.laptop-card:hover {
    transform: translateY(-5px);
}

.laptop-img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    background: #f8f8f8;
}

.laptop-title {
    font-size: 14px;
    height: 40px;
    overflow: hidden;
    margin-top: 5px;
}

.price {
    color: red;
    font-weight: bold;
    font-size: 15px;
    margin-bottom: 0;
}

.laptop-item {
    width: 200px;
    display: inline-block;
    margin: 10px;
    vertical-align: top;
}
</style>

<x-laptop-layout>

    <x-slot name="title">
        Kết quả tìm kiếm
    </x-slot>

    <h5 class="mb-3">
        Kết quả tìm kiếm cho: "{{ $keyword }}"
    </h5>

    <div class="list-laptop">

        @forelse($laptops as $item)
            <div class="laptop-item">

                <div class="card laptop-card shadow-sm border-0 h-100">

                    <a href="{{ url('/laptop/' . $item->id) }}">
                    <img src="{{ asset('storage/image/' . $item->hinh_anh) }}"
                         class="laptop-img p-2"
                         alt="{{ $item->ten }}"> </a>

                    <div class="p-2">

                        <h6 class="laptop-title">
                            {{ $item->tieu_de }}
                        </h6>

                        <p class="price">
                            {{ number_format($item->gia) }} VNĐ
                        </p>

                    </div>

                </div>
            </div>
        @empty
            <p>Không tìm thấy laptop nào</p>
        @endforelse

    </div>

</x-laptop-layout>