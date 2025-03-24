<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Workout Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 relative">

                <!-- Emoji inserted in top-right corner -->
                <span id="workout-emoji" class="absolute top-0 right-0 mr-2 mt-2" style="font-size: 4rem;"></span>


                <h1 id="workout-name" class="text-2xl font-bold text-gray-800 dark:text-gray-200"></h1>
                <p id="workout-description" class="text-gray-700 dark:text-gray-300 mb-4" style="padding-right: 4rem;"></p>
                <p id="workout-created_at" class="text-gray-700 dark:text-gray-300 mb-4" style="padding-right: 4rem;"></p>
                <p id="workout-updated_at" class="text-gray-700 dark:text-gray-300 mb-4" style="padding-right: 4rem;"></p>
                <p id="workout-id" class="text-gray-700 dark:text-gray-300 mb-4 text-xl" style="padding-right: 4rem;"></p>
                <p id="workout-type" class="text-gray-700 dark:text-gray-600 mb-4 text-xl" style="padding-right: 4rem;"></p>
                <p id="workout-is_custom" class="text-gray-700 dark:text-gray-600 mb-4 text-xl" style="padding-right: 4rem;"></p>


                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Exercises</h3>
                <ul id="exercises-list" class="mt-2 space-y-2">
                    <!-- Exercises will be dynamically inserted here -->
                </ul>



                    <!-- Start button-->
                <div class="mt-6 flex justify-between">
                <!-- Delete button-->

                    <!-- Update and delete button container -->
                    <div id="upd-del-workout-btn-container" class="flex gap-6">
                    </div>

                    <button id="start-workout-btn" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                        Start this workout! 🚀
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const urlParts = window.location.pathname.split('/');
            const workoutId = urlParts[urlParts.length - 1];

            const response = await fetch(`/api/workouts/id/${workoutId}`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                console.error('Error fetching workout:', response.status);
                return;
            }

            const workout = await response.json();

            const updDelBtnContainer = document.getElementById('upd-del-workout-btn-container');

            if (workout.is_custom === 1) {
                const deleteButton = document.createElement('button');
                deleteButton.id = 'delete-workout-btn';
                deleteButton.className = 'bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600';
                deleteButton.style.backgroundColor = 'red';
                deleteButton.textContent = 'Delete workout 🗑️';

                deleteButton.addEventListener('click', async () => {

                    const userConfirmed = confirm('Are you sure you want to delete this workout?');

                    if (userConfirmed) {

                        const urlParts = window.location.pathname.split('/');
                        const workoutId = urlParts[urlParts.length - 1];  // Assuming the ID is part of the URL

                        const response = await fetch(`/api/workouts/delete/${workoutId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        });


                        if (response.ok) {
                            alert('Workout deleted successfully!')
                            window.location.href = '/home';

                        } else {

                            alert('Failed to delete workout. Please try again.');
                        }
                    }
                });

                // Append the button to the container
                updDelBtnContainer.appendChild(deleteButton);

                const updateButton = document.createElement('button');
                updateButton.id = 'update-workout-btn';
                updateButton.className = 'bg-teal-500 text-white px-4 py-2 rounded text-sm hover:bg-teal-600';
                updateButton.style.backgroundColor = 'teal';
                updateButton.textContent = 'Update workout 🔄';

                updateButton.addEventListener('click', async () => {
                    window.location.href = `/workouts/update/id/${workout.id}`;
                });

                updDelBtnContainer.appendChild(updateButton);
            }

            // Update the workout name and description
            document.getElementById('workout-name').textContent = workout.name;
            document.getElementById('workout-description').textContent = workout.description || 'No description available';
            document.getElementById('workout-created_at').textContent = `Created at: ${workout.created_at ? new Date(workout.created_at).toLocaleString() : 'No creation date available'}`;

            if (workout.updated_at === workout.created_at) {
                document.getElementById('workout-updated_at').textContent = '';
            } else {
                document.getElementById('workout-updated_at').textContent = `Updated at: ${workout.updated_at ? new Date(workout.updated_at).toLocaleString() : 'No update date available'}`;
            }

            document.getElementById('workout-id').textContent = `ID: ${workout.id}`;

            let emoji;

            switch (workout.type) {
                case 'cardio':
                    emoji = '❤️‍🔥';
                    break;
                case 'strength':
                    emoji = '🏋️‍️';
                    break;
                case 'endurance':
                    emoji = '🏃';
                    break;
                case 'flexibility':
                    emoji = '🤸';
                    break;
                case 'swimming':
                    emoji = '🏊';
                    break;
                case 'dance':
                    emoji = '💃';
                    break;
                default:
                    emoji = '💪';
            }

            document.getElementById('workout-emoji').textContent = emoji;
            document.getElementById('workout-type').textContent = `Type: ${workout.type}`;
            document.getElementById('workout-is_custom').textContent = `Is custom: ${workout.is_custom === 0 ? 'false' : 'true'}`;

            // Exercises list
            const exercisesList = document.getElementById('exercises-list');

            exercisesList.innerHTML = '';


            workout.exercises.forEach(exercise => {

                const li = document.createElement('li');
                li.classList.add('p-2', 'bg-gray-100', 'dark:bg-gray-700', 'rounded-md');
                const exerciseLink = document.createElement('a');

                exerciseLink.href = `/exercises/id/${exercise.id}`;
                exerciseLink.textContent = exercise.name;
                exerciseLink.classList.add('text-blue-500', 'hover:text-blue-600');

                li.appendChild(exerciseLink);
                exercisesList.appendChild(li);
            });

            // Handle "Start this workout" button functionality

            document.getElementById('start-workout-btn').addEventListener('click', () => {
                //
                console.log('Workout started!');
            });

        });
    </script>
</x-app-layout>
