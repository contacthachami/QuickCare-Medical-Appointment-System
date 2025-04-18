/**
 * Doctor Appointment Calendar
 * Displays all doctor appointments in a monthly calendar view
 * Allows doctor to see their schedule at a glance
 */

document.addEventListener("DOMContentLoaded", function () {
    // Get the calendar element
    const calendarEl = document.getElementById("calendar");

    if (!calendarEl) {
        console.error("Calendar element not found");
        return;
    }

    // Create an array to hold all events (appointments and available slots)
    let allEvents = [];

    // Fetch appointments data from the server
    Promise.all([
        fetch("/doctor/appointments/calendar").then((response) => {
            if (!response.ok) throw new Error("Error fetching appointments");
            return response.json();
        }),
        fetch("/doctor/schedules").then((response) => {
            if (!response.ok) throw new Error("Error fetching schedules");
            return response.json();
        }),
    ])
        .then(([appointmentsData, schedulesData]) => {
            // Map the appointments data to FullCalendar event format
            const appointmentEvents = appointmentsData.map((event) => ({
                id: `appt-${event.id}`,
                title: `📅 ${event.title}`,
                start: event.start,
                extendedProps: event.extendedProps || {},
                className: "appointment-event",
                borderColor: "#3182ce",
                backgroundColor: "#3182ce",
                textColor: "#ffffff",
            }));

            // Map the schedules data to FullCalendar event format
            const scheduleEvents = schedulesData.map((schedule, index) => {
                // Format the time nicely
                const formattedStartTime = formatTime(schedule.start);
                const formattedEndTime = formatTime(schedule.end);

                return {
                    id: `sched-${schedule.id}`,
                    title: `${formattedStartTime} - ${formattedEndTime}`,
                    start: schedule.start,
                    end: schedule.end,
                    extendedProps: {
                        type: "schedule",
                        day: schedule.day,
                    },
                    className: "schedule-event",
                    borderColor: "#10B981",
                    backgroundColor: "#D1FAE5",
                    textColor: "#065F46",
                };
            });

            // Combine both types of events
            allEvents = [...appointmentEvents, ...scheduleEvents];

            // Initialize the calendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                events: allEvents,
                eventTimeFormat: {
                    hour: "numeric",
                    minute: "2-digit",
                    meridiem: "short",
                },
                eventDisplay: "block",
                dayMaxEventRows: 3, // Limit number of visible events per day before showing "more" link
                moreLinkClick: "day", // When clicking "more" link, show day view
                eventContent: function (arg) {
                    // Custom rendering for schedule events
                    if (arg.event.id.startsWith("sched-")) {
                        const timeIcon = document.createElement("i");
                        timeIcon.className = "fas fa-clock";
                        timeIcon.style.marginRight = "4px";

                        const availableText = document.createElement("span");
                        availableText.textContent = "Available";
                        availableText.style.marginRight = "5px";

                        const timeText = document.createElement("span");
                        timeText.textContent = arg.event.title;
                        timeText.style.fontWeight = "500";

                        const container = document.createElement("div");
                        container.className = "fc-event-time-container";
                        container.style.padding = "2px 4px";
                        container.style.display = "flex";
                        container.style.alignItems = "center";
                        container.style.flexWrap = "nowrap";
                        container.style.whiteSpace = "nowrap";
                        container.style.overflow = "hidden";
                        container.style.textOverflow = "ellipsis";

                        container.appendChild(timeIcon);
                        container.appendChild(availableText);
                        container.appendChild(timeText);

                        return { domNodes: [container] };
                    }
                    // Default rendering for appointment events
                    return;
                },
                eventClick: function (info) {
                    // When an appointment event is clicked, show the modal with appointment details
                    if (info.event.id.startsWith("appt-")) {
                        const appointmentId = info.event.id.replace(
                            "appt-",
                            ""
                        );
                        const appointmentModal =
                            document.getElementById("appointmentModal");
                        if (appointmentModal) {
                            $(appointmentModal).attr(
                                "data-appointment-id",
                                appointmentId
                            );
                            $(appointmentModal).modal("show");
                        }
                    }
                    // For schedule events, show availability info
                    if (info.event.id.startsWith("sched-")) {
                        alert(
                            `This time slot is available at ${formatTime(
                                info.event.start
                            )} - ${formatTime(info.event.end)}`
                        );
                    }
                },
                eventDidMount: function (info) {
                    // Add tooltips or other UI enhancements
                    if (info.event.extendedProps.type === "schedule") {
                        $(info.el).tooltip({
                            title: `Available: ${formatTime(
                                info.event.start
                            )} - ${formatTime(info.event.end)}`,
                            placement: "top",
                            trigger: "hover",
                            container: "body",
                        });

                        // Apply better styling to events
                        info.el.style.margin = "1px 0";
                        info.el.style.padding = "1px 4px";
                        info.el.style.borderRadius = "4px";
                        info.el.style.fontSize = "0.75rem";
                    }
                },
                dayMaxEvents: false, // When too many events, show the "+more" link
                height: "auto",
                contentHeight: "auto",
                aspectRatio: 1.8,
                themeSystem: "bootstrap",
                views: {
                    timeGrid: {
                        // options apply to timeGridWeek and timeGridDay views
                        dayMaxEventRows: 6,
                        slotEventOverlap: false,
                    },
                    month: {
                        // Month view specific options
                        eventLimit: 3,
                    },
                },
            });

            // Render the calendar
            calendar.render();

            // Add global CSS for calendar events
            const style = document.createElement("style");
            style.textContent = `
                .fc-daygrid-event {
                    margin-top: 2px !important;
                    margin-bottom: 2px !important;
                    padding: 2px 4px !important;
                    border-radius: 4px !important;
                    font-size: 0.75rem !important;
                }
                .fc-daygrid-day-events {
                    padding: 2px !important;
                }
                .fc-daygrid-more-link {
                    font-weight: bold !important;
                    color: #3182ce !important;
                    background: #EBF5FF !important;
                    border-radius: 4px !important;
                    margin-top: 2px !important;
                    padding: 2px 4px !important;
                }
                .fc-h-event {
                    border-width: 0 !important;
                }
                .fc-event-main {
                    padding: 2px !important;
                }
                .schedule-event {
                    background-color: #D1FAE5 !important;
                    border-color: #10B981 !important;
                    color: #065F46 !important;
                }
            `;
            document.head.appendChild(style);
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
            if (calendarEl) {
                calendarEl.innerHTML =
                    '<div class="alert alert-danger p-4">Error loading calendar data. Please refresh the page to try again.</div>';
            }
        });

    // Helper function to format time
    function formatTime(timeStr) {
        // If it's already a Date object
        if (timeStr instanceof Date) {
            return timeStr.toLocaleTimeString("en-US", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            });
        }

        // If it's a time string like "08:00:00"
        if (typeof timeStr === "string" && timeStr.includes(":")) {
            const [hours, minutes] = timeStr.split(":");
            const date = new Date();
            date.setHours(parseInt(hours, 10));
            date.setMinutes(parseInt(minutes, 10));
            return date.toLocaleTimeString("en-US", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            });
        }

        return timeStr; // Return as is if we can't format it
    }
});
