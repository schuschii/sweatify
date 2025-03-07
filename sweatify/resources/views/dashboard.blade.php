<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- User Data and Favorite Exercises Graph in a Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full max-w-[400px]">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">User Information</th>
                            <th class="px-4 py-2 text-left">Favorite Exercises</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <!-- Left Column: User Data -->
                            <td class="px-4 py-2">
                                <h3 class="text-xl font-semibold">User Information</h3>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <p><strong>Weight:</strong> 75 kg</p>
                                        <p><strong>BMI:</strong> 24.5</p>
                                    </div>
                                    <div>
                                        <p><strong>Height:</strong> 180 cm</p>
                                        <p><strong>Age:</strong> 28 years</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Right Column: Favorite Exercises Graph -->
                            <td class="px-4 py-2" style="max-width: 250px;">
                            <div class="mt-4">
                                    <canvas id="favoriteExercisesChart"></canvas>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Workout History Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold">Workout History</h3>
                    <table class="w-full mt-4 table-auto">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Exercise</th>
                            <th class="px-4 py-2 text-left">Duration (mins)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="px-4 py-2">March 6, 2025</td>
                            <td class="px-4 py-2">Push-ups, Squats</td>
                            <td class="px-4 py-2">30</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">March 5, 2025</td>
                            <td class="px-4 py-2">Running, Bicep curls</td>
                            <td class="px-4 py-2">45</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">March 4, 2025</td>
                            <td class="px-4 py-2">Yoga, Planks</td>
                            <td class="px-4 py-2">60</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    <!-- Chart.js Script for Favorite Exercises -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('favoriteExercisesChart').getContext('2d');
        const favoriteExercisesChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Push-ups', 'Squats', 'Running', 'Bicep Curls', 'Yoga'],
                datasets: [{
                    data: [20, 15, 25, 10, 30], // Fake data (percentages)
                    backgroundColor: [
                        '#4CAF50', '#FF9800', '#2196F3', '#FF5722', '#9C27B0'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>
