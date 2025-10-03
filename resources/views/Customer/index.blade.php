<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" x-transition
            class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('create.route') }}" method="POST" class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded">
        @csrf
        <h2 class="text-2xl font-bold mb-6 text-center">Request a Quote</h2>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Name <span
                    class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" id="name"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
            @error('name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email <span
                    class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" id="email"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone <span
                    class="text-red-500">*</span></label>
            <input type="tel" name="phone" value="{{ old('phone') }}" id="phone"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror">
            @error('phone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-medium mb-2">Address <span
                    class="text-red-500">*</span></label>
            <input type="text" name="address" value="{{ old('address') }}" id="address"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror">
            @error('address')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="service_id" class="block text-gray-700 font-medium mb-2">Select Service <span
                    class="text-red-500">*</span></label>
            <select name="service_id" id="service_id"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_id') border-red-400 @enderror">
                <option value=""></option>
                @foreach ($services as $service)
                    <option {{ old('service_id') == $service->id ? 'selected' : '' }} value="{{ $service->id }}">
                        {{ $service->name }}</option>
                @endforeach
            </select>
            @error('service_id')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="booking_date" class="block text-gray-700 font-medium mb-2">Booking Date & Time <span
                    class="text-red-500">*</span></label>
            <input type="datetime-local" value="{{ old('booking_date') }}" name="booking_date" id="booking_date"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('booking_date') border-red-400 @enderror">
            @error('booking_date')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="duration" class="block text-gray-700 font-medium mb-2">Duration (hours) <span
                    class="text-red-500">*</span></label>
            <input type="number" name="duration" value="{{ old('duration') }}" id="duration" min="1"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration') border-red-400 @enderror">
            @error('duration')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-gray-700 font-medium mb-2">Additional Notes (Optional)</label>
            <textarea name="notes" id="notes" rows="4"
                class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-center">
            <button
                type="submit"class="cursor-pointer bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Submit Quote Request
            </button>
        </div>
    </form>
</body>

</html>
