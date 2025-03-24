<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
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
                            <div class="grid grid-cols-1 gap-4 mt-4">
                                <div>
                                    <p><strong>Weight:</strong> {{Auth::user()->weight}} kg</p>
                                    <p><strong>BMI:</strong> {{ Auth::user()->bmi ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p><strong>Height:</strong> {{ Auth::user()->height }} cm</p>
                                    <p><strong>Age:</strong> {{ Auth::user()->age }} years</p>
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
        <div>
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-xl font-semibold">Workout History</h3>
                <table class="w-full mt-4 table-auto text-center" id="workout-history-table" style="text-align: center">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-center">Workout</th>
                        <th class="px-4 py-2 text-left text-center">Date</th>
                        <th class="px-4 py-2 text-left text-center" >Exercises</th>
                        <th class="px-4 py-2 text-left text-center">Duration (mins)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Workout History Rows Will be Dynamically Inserted Here -->
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="mt-10 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Weight Progress</h2>
        <canvas id="weightChart"></canvas>
    </div>

    <!-- Chart.js only once -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Weight Progress Chart -->
    <script>
        const weightCtx = document.getElementById('weightChart').getContext('2d');

        const weightData = @json($weightLogs->pluck('weight'));
        const dateLabels = @json($weightLogs->pluck('created_at')->map(fn($d) => $d->format('Y-m-d')));

        new Chart(weightCtx, {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Weight (kg)',
                    data: weightData,
                    borderWidth: 2,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>

    <!-- Favorite Exercises Chart -->
    <script>
        const favoriteCtx = document.getElementById('favoriteExercisesChart').getContext('2d');
        const favoriteExercisesChart = new Chart(favoriteCtx, {
            type: 'pie',
            data: {
                labels: ['Push-ups', 'Squats', 'Running', 'Bicep Curls', 'Yoga'],
                datasets: [{
                    data: [20, 15, 25, 10, 30], // Replace with real data later
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

    <script>
        // Fetch workout history data for the user (use authenticated user ID)
        const userId = {{ Auth::id() }};

        fetch(`/api/workouts/history/${userId}`)
            .then(response => response.json())
            .then(data => {
                const workoutHistoryTable = document.getElementById('workout-history-table').querySelector('tbody');
                workoutHistoryTable.innerHTML = '';

                data.forEach((entry, index) => {



                    const workoutRow = document.createElement('tr');
                    workoutRow.classList.add('border', 'dark:border-gray-300', 'rounded-lg');


                    workoutRow.innerHTML = `
                <td class="px-4 py-4">${entry.workout_name}</td>
                <td class="px-4 py-4">${entry.date}</td>
                <td class="px-4 py-4">
                    <div class="flex flex-col space-y-2">
                        ${entry.exercise_names.map(exercise => `
                            <div class="flex justify-between py-2 px-4 border dark:border-gray-300 rounded-lg">
                                <span class="w-1/3">${exercise.exercise_name}</span>
                                <span class="w-1/3">${exercise.reps} reps</span>
                                <span class="w-1/3">${exercise.weight} kg</span>
                            </div>
                        `).join('')}
                    </div>
                </td>
                <td class="px-4 py-4">${entry.duration}</td>
            `;
                    workoutHistoryTable.appendChild(workoutRow);
                });
            })
            .catch(error => {
                console.error('Error fetching workout history:', error);
            });
    </script>
</x-app-layout>
