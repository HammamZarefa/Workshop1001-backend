 <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <nav class="text-white text-base font-semibold pt-3">
            <a href="<?php echo e(route('admin.home')); ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-shopping-cart mr-3"></i>
                Orders
            </a>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-box mr-3"></i>
                Products
            </a>


        </nav>

    </aside>
<?php /**PATH C:\laravel\Workshop1001-backend\resources\views/components/sidebar.blade.php ENDPATH**/ ?>