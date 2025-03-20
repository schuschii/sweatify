<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">

        <h3 class="text-xl font-semibold">Workouts</h3>
        <button>Start workout!</button>

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <label for="limit" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Number of Workouts per Page:</label>
                            <select id="limit" class="mt-1 block w-full" onchange="fetchWorkouts(true)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>

                            <ul id="workout-list" class="mt-4 space-y-2">
                                <!-- Workout names will be populated here -->
                            </ul>

                            <!-- Pagination Controls -->
                            <div class="mt-4 flex justify-between items-center">
                                <button id="prevPage" class="bg-gray-500 text-white px-4 py-2 rounded disabled:opacity-50" onclick="changePage(-1)">
                                    Previous
                                </button>

                                <span id="page-info" class="text-gray-700 dark:text-gray-300">
                        Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                    </span>

                                <button id="nextPage" class="bg-gray-500 text-white px-4 py-2 rounded disabled:opacity-50" onclick="changePage(1)">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Fetch the workouts -->
    <script>
        let offset = 0;
        let limit = 10;
        let totalWorkouts = 0;

        async function fetchWorkouts(resetOffset = false) {
            if (resetOffset) offset = 0;

            limit = parseInt(document.getElementById('limit').value);
            const response = await fetch(`/api/workouts?limit=${limit}&offset=${offset}`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                console.error('Error fetching workouts:', response.status);
                return;
            }

            const data = await response.json();
            totalWorkouts = data.total;

            const workoutList = document.getElementById('workout-list');
            workoutList.innerHTML = '';

            data.workouts.forEach(workout => {
                const li = document.createElement('li');
                li.classList.add('p-2', 'bg-gray-100', 'dark:bg-gray-700', 'rounded-md', 'cursor-pointer', 'hover:bg-gray-200', 'dark:hover:bg-gray-600', 'dark:text-gray-200');

                let emoji = '';
                if (workout.name.toLowerCase().includes('cardio')) {
                    emoji = '❤️‍🔥';
                } else if (workout.name.toLowerCase().includes('strength')) {
                    emoji = '💪';
                }

                li.innerHTML = `<div class='flex justify-between items-start'>
                                    <div>
                                        <strong>${workout.name}</strong><br>
                                        <span class='text-sm text-gray-600 dark:text-gray-400'>${workout.description || 'No description available'}</span>
                                    </div>
                                    <span class='text-lg'>${emoji}</span>
                                </div>
                                <div class='flex justify-end mt-2'>
                                    <button class='bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600'>
                                        Start this workout! 🚀
                                    </button>
                                </div>`;


                li.setAttribute('data-id', workout.id);
                li.onclick = () => {
                    window.location.href = `/workouts/id/${workout.id}`;
                };

                workoutList.appendChild(li);
            });

            updatePagination();
        }

        function changePage(direction) {
            offset += direction * limit;
            fetchWorkouts();
        }

        function updatePagination() {
            const currentPage = Math.floor(offset / limit) + 1;
            const totalPages = Math.ceil(totalWorkouts / limit);

            document.getElementById('current-page').textContent = currentPage;
            document.getElementById('total-pages').textContent = totalPages;

            document.getElementById('prevPage').disabled = offset <= 0;
            document.getElementById('nextPage').disabled = offset + limit >= totalWorkouts;
        }

        document.addEventListener('DOMContentLoaded', fetchWorkouts);
    </script>

</x-app-layout>
