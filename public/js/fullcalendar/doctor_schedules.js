document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "timeGridWeek",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        firstDay: 1, // Set Monday as the first day of the week
        events: {
            url: "/doctor/schedules/index", // Endpoint to fetch schedules
            method: "GET",
            failure: function () {
                console.error("Error fetching schedules.");
            },
            // Custom function to parse the response data
            eventDataTransform: function (eventData) {
                return {
                    id: eventData.id,
                    title: eventData.title,
                    start: eventData.start,
                    end: eventData.end,
                    backgroundColor: "#D1FAE5",
                    borderColor: "#10B981",
                    textColor: "#065F46",
                    classNames: ["schedule-event"],
                };
            },
        },
        eventDisplay: "block",
        slotEventOverlap: false, // Prevents event overlapping
        slotMinTime: "07:00:00", // Start time of the day view
        slotMaxTime: "20:00:00", // End time of the day view
        allDaySlot: false, // Hide the all-day slot at the top
        slotDuration: "00:30:00", // 30-minute slots
        slotLabelInterval: "01:00", // Hour labels
        expandRows: true, // Expand the rows to fill the available height
        height: "auto",
        contentHeight: "auto",
        eventContent: function (arg) {
            const timeText = document.createElement("span");
            timeText.className = "fc-event-time";

            // Extract the time part from the title if it contains a time range
            const titleParts = arg.event.title.split(":");
            if (titleParts.length > 1) {
                timeText.textContent = titleParts[1].trim();
            } else {
                // Format the time from the event start
                const start = new Date(arg.event.start);
                const end = new Date(arg.event.end);
                timeText.textContent = `${start.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                })} - ${end.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                })}`;
            }

            // Create a container for the event content
            const container = document.createElement("div");
            container.className = "fc-event-content-wrapper";
            container.style.display = "flex";
            container.style.alignItems = "center";
            container.style.padding = "2px 4px";

            // Add an icon and the "Available" text
            const iconElement = document.createElement("i");
            iconElement.className = "fas fa-clock";
            iconElement.style.marginRight = "4px";

            const availableText = document.createElement("div");
            availableText.textContent = "Available";
            availableText.style.fontWeight = "bold";
            availableText.style.marginRight = "5px";

            container.appendChild(iconElement);
            container.appendChild(availableText);
            container.appendChild(timeText);

            return { domNodes: [container] };
        },
        eventDidMount: function (info) {
            // Add tooltip with time information
            $(info.el).tooltip({
                title: `Available: ${info.event.start.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                })} - ${info.event.end.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                })}`,
                placement: "top",
                trigger: "hover",
                container: "body",
            });

            // Apply better styling
            info.el.style.margin = "1px 0";
            info.el.style.fontSize = "0.8rem";
            info.el.style.borderRadius = "4px";
        },
    });

    calendar.render();

    // Add additional CSS for better event styling
    const style = document.createElement("style");
    style.textContent = `
        .fc-timegrid-event {
            padding: 2px 4px !important;
            border-radius: 4px !important;
        }
        .fc-v-event {
            border: none !important;
        }
        .fc-timegrid-slot {
            height: 2.5em !important;
        }
        .fc-col-header-cell {
            background-color: #EBF8FF !important;
            padding: 8px 0 !important;
        }
        .fc-timegrid-axis {
            background-color: #EBF8FF !important;
        }
        .fc-timegrid-slot-label {
            font-size: 0.8rem !important;
        }
        .fc-timegrid-now-indicator-line {
            border-color: #E53E3E !important;
            border-width: 2px !important;
        }
        .schedule-event {
            background-color: #D1FAE5 !important;
            border-color: #10B981 !important;
            color: #065F46 !important;
        }
    `;
    document.head.appendChild(style);
});
