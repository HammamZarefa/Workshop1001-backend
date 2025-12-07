 <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <nav class="text-white text-base font-semibold pt-3">
            <a href="{{route('admin.home')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-shopping-cart mr-3"></i>
                Orders
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-box mr-3"></i>
                Products
            </a>
             <a href="{{ route('admin.categories.index') }}"
           class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
            <i class="fas fa-tags mr-3"></i>
            Categories
        </a>
            <a href="{{ route('admin.banners.index') }}"
            class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
              <i class="fas fa-image mr-3"></i>
              Banners
        </a>
            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-users mr-3"></i>
                Roles
            </a>
            <a href="{{ route('admin.permissions.index') }}"
               class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-lock mr-3"></i>
                Permissions
            </a>


        </nav>

    </aside>
