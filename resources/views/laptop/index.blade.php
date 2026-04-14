<x-laptop-layout>
    <x-slot name="title">Trang chủ Laptop</x-slot>

    <div class="mt-4">
        
        <div class="col-12 p-0">
            
            <div class="mb-4 d-flex justify-content-center align-items-center">

                <span class="mr-2 font-weight-bold text-dark">
                    Tìm kiếm theo:
                </span>

                <a href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}" 
                class="btn {{ request('sort') == 'asc' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm mr-2">
                    Giá tăng dần
                </a>
                
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}" 
                class="btn {{ request('sort') == 'desc' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">
                    Giá giảm dần
                </a>

            </div>
            </div>

            <div class="row">
                @forelse($laptops as $item)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 border" style="border-radius: 4px;">
                            
                            <div class="p-2 text-center bg-white">
                                <a href="{{ url('/laptop/' . $item->id) }}">
                                <img src="{{ asset('storage/image/' . $item->hinh_anh) }}" 
                                     class="img-fluid" 
                                     alt="{{ $item->tieu_de }}" 
                                     style="height: 160px; object-fit: contain;">
                                </a>
                            </div>
                            
                            <div class="card-body text-center p-2 d-flex flex-column bg-white">
                                <h6 class="card-title text-dark mb-2" style="font-size: 14px; line-height: 1.4;">
                                    {{ $item->tieu_de }}
                                </h6>
                                
                                <p class="card-text text-danger font-weight-bold mt-auto mb-1" style="font-size: 15px;">
                                    {{ number_format($item->gia, 0, ',', '.') }} đ
                                </p>
                            </div>
                            
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Không có sản phẩm nào phù hợp.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-laptop-layout>