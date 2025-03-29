@php
    $phones = \App\Models\Phone::all();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ "MobShop Dashboard" }}
            </h2>
            <a href="{{ route('site') }}" class="text-xl font-italic text-gray-800 dark:text-gray-200 hover:text-blue-500">
                {{ "Visit MobShop Site" }}
            </a>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Create Button -->
            <div class="flex justify-end mb-4">
                <button id="openCreateModal"
                        class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    + Add New Phone
                </button>
            </div>

            <!-- Phones Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse rounded-lg shadow-lg">
                    <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <th class="py-3 px-4 border-b text-center">#</th>
                        <th class="py-3 px-4 border-b text-left">Image</th>
                        <th class="py-3 px-4 border-b text-left">Name</th>
                        <th class="py-3 px-4 border-b text-left">Category Name</th>
                        <th class="py-3 px-4 border-b text-center">Price</th>
                        <th class="py-3 px-4 border-b text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($phones as $phone)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border-b transition duration-300">
                            <td class="py-3 px-4 text-center">{{ $phone->id }}</td>
                            <td class="py-3 px-4">
                                @if($phone->image)
                                    <img src="{{ asset('storage/' . $phone->image) }}" alt="{{ $phone->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $phone->name }}</td>
                            <td class="py-3 px-4">{{ $phone->category_name }}</td>
                            <td class="py-3 px-4 text-center">{{ $phone->price }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="openEditModal({{ $phone->id }}, '{{ $phone->name }}', '{{ $phone->category_name }}', '{{ $phone->price }}', '{{ $phone->image }}')"
                                            class="edit-button bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-1 px-3 rounded">
                                        Edit
                                    </button>
                                    <form action="{{ route('phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if ($phones->isEmpty())
                <div class="text-center mt-6 text-gray-500 dark:text-gray-400">
                    No phones available.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="phoneModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
            <h3 id="modalTitle" class="text-lg font-bold mb-4">Create Phone</h3>

            <!-- Form -->
            <form id="phoneForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="phoneId">

                <div class="mb-4">
                    <input type="text" id="name" name="name" class="w-full border rounded p-2" placeholder="Phone Name" required>
                </div>
                <div class="mb-4">
                    <input type="text" id="category_name" name="category_name" class="w-full border rounded p-2" placeholder="Category Name" required>
                </div>
                <div class="mb-4">
                    <input type="number" id="price" name="price" class="w-full border rounded p-2" placeholder="Price" required>
                </div>
                <div class="mb-4">
                    <input type="file" id="image" name="image" class="w-full border rounded p-2">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="submit"
                            class="bg-black hover:bg-black text-blue font-bold py-2 px-4 rounded">
                        Save
                    </button>
                    <button type="button" id="closeModal"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Open Create Modal
        $('#openCreateModal').on('click', function () {
            $('#modalTitle').text('Add New Phone');
            $('#phoneId').val('');
            $('#name').val('');
            $('#category_name').val('');
            $('#price').val('');
            $('#phoneModal').removeClass('hidden');
        });

        // Open Edit Modal
        window.openEditModal = function (id, name, category_name, price, image) {
            $('#modalTitle').text('Edit Phone');
            $('#phoneId').val(id);
            $('#name').val(name);
            $('#category_name').val(category_name);
            $('#price').val(price);
            $('#phoneModal').removeClass('hidden');
        };

        // Submit Form via AJAX
        $('#phoneForm').on('submit', function (e) {
            e.preventDefault();
            const id = $('#phoneId').val();
            const url = id ? `/phones/${id}` : `/phones`;
            const method = id ? 'PATCH' : 'POST';
            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    $('#phoneModal').addClass('hidden');
                    location.reload();
                },
                error: function (response) {
                    alert(response.responseJSON.message);
                }
            });
        });

        // Close Modal
        $('#closeModal').on('click', function () {
            $('#phoneModal').addClass('hidden');
        });
    </script>
</x-app-layout>
