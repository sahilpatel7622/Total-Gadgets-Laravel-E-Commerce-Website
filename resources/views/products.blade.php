@extends('layouts.user')

@section('title','Products')

@section('content')

<style>

.products-page{
    max-width:1280px;
    margin:auto;
    padding:20px;
}

/* Hero */

.products-hero{
    background:linear-gradient(135deg,#2563eb,#6d28d9);
    color:#fff;
    padding:35px 40px;
    border-radius:18px;
    margin-bottom:25px;
    text-align: center
}

.products-hero h1{
    font-size:38px;
    margin-bottom:8px;
    font-weight:700;
}

.products-hero p{
    font-size:17px;
}

/* Toolbar */

.products-toolbar{
    position:sticky;
    top:75px;
    z-index:100;
    background:#fff;
    padding:16px;
    border-radius:14px;
    margin-bottom:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.toolbar-row{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
}

.search-form{
    display:flex;
    flex:1;
    min-width:250px;
}

.search-form input{
    width:70%;
    padding:11px 14px;
    border:1px solid #ddd;
    border-radius:8px 0 0 8px;
    outline:none;
    font-size:14px;
}

.search-form button{
    border:none;
    background:#2563eb;
    color:#fff;
    padding:0 20px;
    border-radius:0 8px 8px 0;
    cursor:pointer;
}

.category-filters{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    position: relative;
    right: 55px;
}

.filter-chip{
    padding:8px 15px;
    border-radius:25px;
    text-decoration:none;
    border:1px solid #dbeafe;
    color:#2563eb;
    font-size:13px;
    font-weight:600;
}

.filter-chip.active,
.filter-chip:hover{
    background:#2563eb;
    color:#fff;
}

.sort-form{
    display:flex;
    align-items:center;
    gap:8px;
    position: relative;
    right: 30px;
}

.sort-form label{
    font-size:14px;
}

.sort-form select{
    padding:8px 12px;
    border:1px solid #ddd;
    border-radius:8px;
    font-size:14px;
}

.results-meta{
    margin-left:auto;
    font-size:14px;
    color:#64748b;
    position: relative;
    right: 15px;
}

/* Product Grid */

.products-grid{
    display:grid;
    grid-template-columns:repeat(3,380px);
    gap:40px;
    justify-content:center;
}

.product-card{
    width:370px;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    border:1px solid #eee;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:.3s;
    display:flex;
    flex-direction:column;
}

.product-card:hover{
    transform:translateY(-5px);
    box-shadow:0 12px 25px rgba(0,0,0,.12);
}

.product-img-box{
    height:180px;
    background:#fafafa;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:15px;
    position: relative;
}

.product-img-box a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.wishlist-toggle{
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fff;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    font-size: 16px;
    transition: 0.3s;
    text-decoration: none;
    z-index: 2;
}

.wishlist-toggle:hover {
    transform: scale(1.1);
}

.product-img{
    width:100%;
    height:100%;
    object-fit:contain;
    transition:.3s;
}

.product-card:hover .product-img{
    transform:scale(1.05);
}

.product-content{
    padding:15px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.category-badge{
    display:inline-block;
    background:#eef4ff;
    color:#2563eb;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    margin-bottom:8px;
    align-self: flex-start;
}

.product-name{
    font-size:20px;
    font-weight:700;
    color:#111827;
    line-height:1.3;
    min-height:48px;
    transition: .3s;
    margin-bottom:8px;
}

a .product-name{
    text-decoration: none;
}

a{
    text-decoration: none;
}

a:hover .product-name{
    color: #2563eb;
}

.product-link{
    text-decoration: none;
}

.product-link .product-name{
    color: #111827;
    transition: .3s;
}

.product-link:hover .product-name{
    color: #2563eb;
}

.product-desc{
    font-size:13px;
    color:#64748b;
    line-height:1.5;
    height:55px;
    overflow:hidden;
}

.product-footer{
    margin-top:auto;
    padding-top:14px;
    border-top:1px solid #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.price{
    font-size:24px;
    font-weight:800;
    color:#2563eb;
}

.view-btn{
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
}

.view-btn:hover{
    background:#1d4ed8;
    color:#fff;
}

.empty-state{
    grid-column:1/-1;
    background:#fff;
    padding:60px;
    border-radius:15px;
    text-align:center;
}

.empty-state{
    grid-column:1/-1;
    background:#fff;
    border-radius:18px;
    padding:70px 30px;
    text-align:center;
    border:1px solid #e5e7eb;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.empty-icon{
    width:90px;
    height:90px;
    margin:0 auto 20px;
    border-radius:50%;
    background:#eef4ff;
    color:#2563eb;
    font-size:42px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.empty-state h3{
    font-size:28px;
    color:#111827;
    margin-bottom:12px;
    font-weight:700;
}

.empty-state p{
    max-width:500px;
    margin:0 auto 28px;
    color:#6b7280;
    font-size:15px;
    line-height:1.7;
}

.reset-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:12px 28px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    transition:.3s;
}

.reset-btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
    color:#fff;
}

.product-image-link{
    display:block;
    width:100%;
    height:100%;
    cursor:pointer;
}

.product-image{
    max-width:100%;
    max-height:160px;
    object-fit:contain;
    display:block;
}
.reset-btn{
    margin-left: 10px;
}

.reset-btn button,
#clearFilters{
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
    white-space: nowrap;
}

.reset-btn button:hover,
#clearFilters:hover{
    background: #1d4ed8;
}

/* Responsive */

@media(max-width:992px){

.products-grid{
grid-template-columns:repeat(2,1fr);
}

}

@media(max-width:768px){
.products-toolbar{
    position:static;
}

.toolbar-row{
    flex-direction:column;
    align-items:stretch;
    gap:15px;
}

.search-form{
    width:100%;
    min-width:100%;
}

.search-form input{
    width:100%;
}

.category-filters{
    position:static;
    right:auto;
    justify-content:flex-start;
}

.sort-form{
    position:static;
    right:auto;
    width:100%;
    justify-content:flex-start;
}

.results-meta{
    position:static;
    right:auto;
    margin:0;
}

#clearFilters{
    width:100%;
    height:42px;
    margin-top:5px;
}

.products-grid{
grid-template-columns:1fr;
}

.product-footer{
flex-direction:column;
align-items:flex-start;
gap:12px;
}

.view-btn{
width:100%;
text-align:center;
}

}

</style>

<div class="products-page">

    <div class="products-hero">
        <h1>Shop Our Products</h1>
        <p>Browse curated items across categories. Find quality products at competitive prices.</p>
    </div>

    <div class="products-toolbar" id="products-toolbar">
        <div class="toolbar-row">

            <form action="{{ route('products') }}" method="GET" class="search-form" id="searchForm">
                <input type="text"
                       name="search"
                       id="searchInput"
                       placeholder="Search by product or category..."
                       value="{{ request('search') }}">

                <button type="submit">Search</button>
            </form>

            <div class="category-filters">
                <a href="#" data-category="" class="filter-chip ajax-category active">All</a>

                @foreach($categories as $cat)
                    <a href="#"
                    data-category="{{ $cat->slug }}"
                    class="filter-chip ajax-category">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('products') }}" method="GET" class="sort-form" id="sortForm">
                <label for="sort">Sort by</label>

                <select name="sort" id="sort">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                        Newest first
                    </option>

                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                        Price: Low to High
                    </option>

                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                        Price: High to Low
                    </option>

                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                        Name: A to Z
                    </option>
                </select>
            </form>

            <div class="results-meta">
                Showing <strong>{{ $product->count() }}</strong>
                product{{ $product->count() === 1 ? '' : 's' }}
            </div>

            <button type="button" id="clearFilters" class="btn btn-secondary">
                Reset
            </button>

        </div>
    </div>

    <div class="products-grid" id="productsGrid">
        @include('product_ajax')
    </div>

</div>

<script>

let selectedCategory = "{{ request('category') ?? '' }}";

function fetchProducts() {
    let search = document.getElementById('searchInput').value;
    let sort = document.getElementById('sort').value;

    let params = new URLSearchParams();

    if (search !== '') {
        params.append('search', search);
    }

    if (selectedCategory !== '') {
        params.append('category', selectedCategory);
    }

    if (sort !== '') {
        params.append('sort', sort);
    }

    let url = "{{ route('products') }}?" + params.toString();

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('productsGrid').innerHTML = data.html;

        document.querySelector('.results-meta').innerHTML =
            `Showing <strong>${data.count}</strong> product${data.count == 1 ? '' : 's'}`;

    })
    .catch(error => {
        console.log(error);
    });
}

document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchProducts();
});

document.getElementById('sort').addEventListener('change', function(e) {
    e.preventDefault();
    fetchProducts();
});

document.querySelectorAll('.ajax-category').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        selectedCategory = this.dataset.category;

        document.querySelectorAll('.ajax-category').forEach(function(item) {
            item.classList.remove('active');
        });

        this.classList.add('active');

        fetchProducts();
    });
});

document.addEventListener('click', function(e) {
    if (e.target.closest('#clearFilters')) {
        e.preventDefault();

        document.getElementById('searchInput').value = '';
        document.getElementById('sort').value = 'newest';
        selectedCategory = '';

        document.querySelectorAll('.ajax-category').forEach(function(item) {
            item.classList.remove('active');
        });

        document.querySelector('.ajax-category[data-category=""]').classList.add('active');

        fetchProducts();
    }
});
</script>

@endsection