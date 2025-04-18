# Appointment Service Microservice - Design Plan

**1. Responsibilities:**

-   **Manage Doctor Schedules:** Create, read, update, and delete doctor availability slots (schedules).
-   **Query Availability:** Provide endpoints to find available appointment slots based on criteria like doctor ID, specialty, date range.
-   **Book Appointments:** Create new appointments for patients with specific doctors at available times.
-   **Manage Appointments:** Allow retrieval, updating (e.g., status change), and cancellation of appointments by authorized users (patients, doctors, potentially admins).
-   **Data Ownership:** Owns the `schedules` and `appointments` data. It will need relevant IDs (user, patient, doctor) but won't manage the user/patient/doctor details themselves.

**2. Technology Stack (Suggestion):**

-   **Language/Framework:** Node.js with Express.js (Lightweight, good for APIs, different from the main PHP app to demonstrate microservice independence).
-   **Database:** A separate database instance (e.g., PostgreSQL, MySQL/MariaDB, or even a NoSQL option like MongoDB if appropriate for schedule/appointment structure).
-   **Communication:** RESTful API over HTTP/S.

**3. Data Model (Internal to Microservice):**

-   **Schedule:**
    -   `schedule_id` (Primary Key)
    -   `doctor_id` (Foreign Key - references user/doctor in User Service)
    -   `start_time` (DateTime)
    -   `end_time` (DateTime)
    -   `status` (e.g., 'available', 'booked', 'unavailable')
    -   `created_at`, `updated_at`
-   **Appointment:**
    -   `appointment_id` (Primary Key)
    -   `schedule_id` (Foreign Key - links to the specific slot)
    -   `patient_id` (Foreign Key - references user/patient in User Service)
    -   `doctor_id` (Foreign Key - references user/doctor in User Service)
    -   `appointment_time` (DateTime - specific time within the schedule slot)
    -   `status` (e.g., 'scheduled', 'completed', 'cancelled_by_patient', 'cancelled_by_doctor', 'no_show')
    -   `cancellation_reason` (Text, optional)
    -   `notes` (Text, optional)
    -   `created_at`, `updated_at`

**4. API Endpoints (RESTful):**

-   **Schedules:**
    -   `POST /schedules`: Create a new schedule/availability slot for a doctor. (Requires Doctor Auth)
        -   Body: `{ doctor_id, start_time, end_time }`
    -   `GET /schedules?doctor_id={id}&start_date={date}&end_date={date}`: Get schedules for a doctor within a date range. (Requires Doctor/Admin Auth)
    -   `PUT /schedules/{schedule_id}`: Update a schedule slot (e.g., mark as unavailable). (Requires Doctor Auth)
    -   `DELETE /schedules/{schedule_id}`: Delete a schedule slot. (Requires Doctor Auth)
-   **Availability:**
    -   `GET /availability?doctor_id={id}&date={date}`: Get available time slots for a specific doctor on a specific date. (Public or Patient Auth)
    -   `GET /availability?specialty_id={id}&date={date}`: Get available slots for any doctor with a given specialty. (Public or Patient Auth) _(Requires interaction with a potential Doctor/User service to map specialty to doctor IDs)_
-   **Appointments:**
    -   `POST /appointments`: Book a new appointment. (Requires Patient Auth)
        -   Body: `{ schedule_id, patient_id, doctor_id, appointment_time, notes? }`
    -   `GET /appointments?patient_id={id}`: Get appointments for a specific patient. (Requires Patient Auth matching ID)
    -   `GET /appointments?doctor_id={id}`: Get appointments for a specific doctor. (Requires Doctor Auth matching ID)
    -   `GET /appointments/{appointment_id}`: Get details of a specific appointment. (Requires Patient/Doctor/Admin Auth involved in the appointment)
    -   `PUT /appointments/{appointment_id}/status`: Update appointment status (e.g., cancel, complete). (Requires Patient/Doctor Auth)
        -   Body: `{ status, cancellation_reason? }`

**5. Interactions with Other Services (Conceptual):**

```mermaid
graph TD
    A[API Gateway / Frontend] -->|Request| AS(Appointment Service);
    AS -->|Query User Info| US(User Service);
    US -->|User Details| AS;
    AS -->|Send Notification Event| NS(Notification Service);

    subgraph Appointment Service
        direction LR
        API(API Endpoints) --> Logic(Business Logic);
        Logic --> DB[(Appointment DB)];
    end

    subgraph User Service (Hypothetical)
        direction LR
        UserAPI(API) --> UserDB[(User DB)];
    end

     subgraph Notification Service (Hypothetical)
        direction LR
        NotifAPI(API/Queue Listener) --> NotifLogic(Send Logic);
    end
```

-   **User Service:** The Appointment Service would need to query a User Service (or the main monolith initially) to validate `patient_id` and `doctor_id` and potentially fetch basic details if needed for context (though ideally, it should only store IDs).
-   **Notification Service:** When an appointment is booked or cancelled, the Appointment Service could publish an event (e.g., to a message queue) or call an API endpoint on a Notification Service to handle sending emails/SMS.
-   **API Gateway/Frontend:** The main application frontend or an API gateway would route requests to the appropriate service.

**6. Error Handling:**

-   Use standard HTTP status codes (400 Bad Request, 401 Unauthorized, 403 Forbidden, 404 Not Found, 409 Conflict - e.g., slot already booked, 500 Internal Server Error).
-   Provide clear JSON error messages: `{ "error": "Short description", "message": "Detailed explanation" }`.
-   Implement validation for all inputs.
