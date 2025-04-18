/**
 * Appointment Details Modal Handler
 * Handles the loading and display of appointment details in a modal when
 * an appointment is clicked in the calendar
 */

$(document).ready(function () {
    // When the appointment modal is shown, fetch appointment details
    $("#appointmentModal").on("shown.bs.modal", function () {
        const appointmentId = $(this).attr("data-appointment-id");
        if (!appointmentId) {
            console.error("No appointment ID provided");
            return;
        }

        // Fetch appointment details from the server
        fetch(`/doctor/appointments/${appointmentId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Display appointment details in the modal
                const modalBody = document.getElementById(
                    "appointmentModalBody"
                );

                modalBody.innerHTML = `
                    <div class="appointment-details">
                        <div class="mb-4 pb-3 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-blue-600">Patient Information</h3>
                            <p class="text-gray-800"><span class="font-semibold">Name:</span> ${
                                data.extendedProps.patientName
                            }</p>
                        </div>
                        
                        <div class="mb-4 pb-3 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-blue-600">Appointment Information</h3>
                            <p class="text-gray-800"><span class="font-semibold">Date & Time:</span> ${formatDateTime(
                                data.start
                            )}</p>
                            <p class="text-gray-800"><span class="font-semibold">Reason:</span> ${
                                data.extendedProps.reason
                            }</p>
                            <p class="text-gray-800"><span class="font-semibold">Doctor:</span> ${
                                data.extendedProps.doctorName
                            }</p>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="/doctor/appointment/${
                                data.id
                            }/details" class="btn btn-primary inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                View Full Details
                            </a>
                        </div>
                    </div>
                `;
            })
            .catch((error) => {
                console.error("Error fetching appointment details:", error);
                const modalBody = document.getElementById(
                    "appointmentModalBody"
                );
                modalBody.innerHTML = `<div class="alert alert-danger">Error loading appointment details. Please try again.</div>`;
            });
    });

    /**
     * Format a date string into a more readable format
     */
    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
        });
    }
});
