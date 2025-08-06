<div class="mb-4 flex items-center justify-between">
    <label for="toggle_orders" class="flex items-center cursor-pointer">
        <span class="mr-3 text-gray-700">Accepting Orders</span>
        <div class="relative w-14 h-8">
            <input
                type="checkbox"
                id="toggle_orders"
                class="sr-only peer"
                {{ $vendor->is_accepting_orders ? 'checked' : '' }}
            >
            <div class="w-full h-full bg-[#d4d9de] rounded-full shadow-inner transition-all duration-300 peer-checked:bg-green-500"></div>
            <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300
                peer-checked:translate-x-6">
            </div>
        </div>
    </label>
</div>

<!--JQuery CDN for AJAX Purposes-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#toggle_orders').on('change', function () {
            let isAccepting = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('vendor.toggle.accepting') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_accepting_orders: isAccepting
                },
                success: function (response) {
                    console.log(response.message);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>