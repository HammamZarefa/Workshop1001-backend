@extends('layouts.main')

@section('title', 'Order #'.$order->id)

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-gray-200 rounded">Back</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Summary</h2>
            <div class="space-y-1 text-sm">
                <div><span class="font-medium">Status:</span> {{ ucfirst($order->status) }}</div>
                <div><span class="font-medium">Currency:</span> {{ $order->currency }}</div>
                <div><span class="font-medium">Total:</span> {{ number_format($order->total, 2) }}</div>
                <div><span class="font-medium">Created:</span> {{ $order->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Customer</h2>
            <div class="space-y-1 text-sm">
                <div><span class="font-medium">Email:</span> {{ $order->user->email ?? '-' }}</div>
                <div><span class="font-medium">Name:</span> {{ trim(($order->user->first_name ?? '').' '.($order->user->last_name ?? '')) ?: '-' }}</div>
                <div><span class="font-medium">Shipping:</span> {{ $order->shipping_address }}</div>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Payment</h2>
            @if($order->payment)
                <div class="space-y-1 text-sm">
                    <div><span class="font-medium">Provider:</span> {{ $order->payment->provider }}</div>
                    <div><span class="font-medium">Method:</span> {{ $order->payment->method }}</div>
                    <div><span class="font-medium">Status:</span> {{ $order->payment->status }}</div>
                    <div><span class="font-medium">Amount:</span> {{ number_format($order->payment->amount, 2) }} {{ $order->payment->currency }}</div>
                    <div><span class="font-medium">Reference:</span> {{ $order->payment->reference }}</div>
                </div>
            @else
                <div class="text-sm text-gray-500">No payment record.</div>
            @endif
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-3">Items</h2>
        <table class="min-w-full">
            <thead>
                <tr class="text-left border-b">
                    <th class="p-2">Product</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr class="border-b">
                        <td class="p-2">{{ $item->product->title ?? ('#'.$item->product_id) }}</td>
                        <td class="p-2">{{ number_format($item->price, 2) }}</td>
                        <td class="p-2">{{ $item->quantity }}</td>
                        <td class="p-2">{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="4">No items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-3">Add Item</h2>
        <form method="POST" action="{{ route('admin.orders.items.add', $order->id) }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @csrf
            <div class="md:col-span-2">
                <input type="hidden" name="product_id" id="product_id" required>
                <input type="text" id="product_search" class="border rounded p-2 w-full" placeholder="Search product by name" autocomplete="off">
                <div id="product_results" class="mt-1 bg-white border rounded shadow max-h-48 overflow-auto hidden"></div>
            </div>
            <input type="number" step="0.01" name="price" id="price" class="border rounded p-2 bg-gray-100" placeholder="Price" readonly>
            <input type="number" step="1" name="quantity" class="border rounded p-2" placeholder="Quantity" required>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
        </form>
        @if ($errors->any())
            <div class="mt-3 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="mt-4 flex gap-2">
    <form method="POST" action="{{ route('admin.orders.status.update', $order->id) }}">
        @csrf
        <select name="status" class="border rounded p-2">
            @foreach(\App\Models\Order::allowedStatuses() as $status)
                @if($order->status !== $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endif
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Update Status</button>
    </form>

    <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}">
        @csrf
        <input type="text" name="reason" placeholder="Cancel reason" class="border rounded p-2">
        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Cancel Order</button>
    </form>
</div>

    @push('scripts')
    <script>
        const searchInput = document.getElementById('product_search');
        const resultsBox = document.getElementById('product_results');
        const hiddenProductId = document.getElementById('product_id');

        let controller = null;

        function clearResults() {
            resultsBox.innerHTML = '';
            resultsBox.classList.add('hidden');
        }

        function renderResults(items) {
            resultsBox.innerHTML = '';
            if (!items.length) { clearResults(); return; }
            items.forEach(item => {
                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-full text-left px-3 py-2 hover:bg-gray-100';
                row.textContent = `${item.title} (ID: ${item.id})`;
                row.addEventListener('click', () => {
                    hiddenProductId.value = item.id;
                    searchInput.value = item.title;
                    const priceInput = document.getElementById('price');
                    if (priceInput) priceInput.value = item.price;
                    clearResults();
                });
                resultsBox.appendChild(row);
            });
            resultsBox.classList.remove('hidden');
        }

        searchInput.addEventListener('input', async (e) => {
            const q = e.target.value.trim();
            hiddenProductId.value = '';
            const priceInput = document.getElementById('price');
            if (priceInput) priceInput.value = '';
            if (q.length < 2) { clearResults(); return; }
            try {
                if (controller) controller.abort();
                controller = new AbortController();
                const res = await fetch(`{{ route('admin.products.search') }}?q=${encodeURIComponent(q)}`, { signal: controller.signal });
                if (!res.ok) return clearResults();
                const data = await res.json();
                renderResults(data);
            } catch (err) {
                clearResults();
            }
        });

        document.addEventListener('click', (e) => {
            if (!resultsBox.contains(e.target) && e.target !== searchInput) {
                clearResults();
            }
        });
    </script>
    @endpush
@endsection
