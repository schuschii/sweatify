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


                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Exercises</h3>
                <ul id="exercises-list" class="mt-2 space-y-2">
                    <!-- Exercises will be dynamically inserted here -->
                </ul>



                    <!-- Start button-->
                <div class="mt-6 flex justify-between">
                <!-- Delete button-->

                    <button id="delete-workout-btn" class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600" style="background-color: red">
                        Delete workout 🗑️
                    </button>

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

            // Update the workout name and description
            document.getElementById('workout-name').textContent = workout.name;
            document.getElementById('workout-description').textContent = workout.description || 'No description available';

            let emoji = '';
            if (workout.name.toLowerCase().includes('cardio')) {
                emoji = '❤️‍🔥';
            } else if (workout.name.toLowerCase().includes('strength')) {
                emoji = '💪';
            }
            document.getElementById('workout-emoji').textContent = emoji;

            // Exercises list
            const exercisesList = document.getElementById('exercises-list');

            exercisesList.innerHTML = '';


            workout.exercises.forEach(exercise => {

                const li = document.createElement('li');
                li.classList.add('p-2', 'bg-gray-100', 'dark:bg-gray-700', 'rounded-md');
                const exerciseLink = document.createElement('a');

                exerciseLink.href = `/exercises/id/${exercise.id}`; // Link to the exercise details page
                exerciseLink.textContent = exercise.name;
                exerciseLink.classList.add('text-blue-500', 'hover:text-blue-600');

                li.appendChild(exerciseLink);
                exercisesList.appendChild(li);
            });

            // Handle "Start this workout" button functionality

            document.getElementById('start-workout-btn').addEventListener('click', () => {
                // You can add functionality here for starting the workout, e.g., navigating to a workout session page.
                console.log('Workout started!');
            });


            document.getElementById('delete-workout-btn').addEventListener('click', async () => {
                // Show confirmation popup
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
        });
    </script>
</x-app-layout>
