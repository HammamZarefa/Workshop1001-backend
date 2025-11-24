<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">

        <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>

        <form method="POST" action="/admin/login" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input type="email"
                       name="email"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="admin@example.com">
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Password</label>
                <input type="password"
                       name="password"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="••••••••">
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Login
            </button>
        </form>

    </div>

</body>
</html>
