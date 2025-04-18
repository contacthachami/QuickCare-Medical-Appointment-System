/**
 * QuickCare Microservices Architecture Animation
 * This script adds interactive animations to the microservices architecture diagram
 * to visualize data flow and service interactions.
 */

// Global variables
let svgDoc = null;
let animationRunning = false;
let currentAnimation = null;

document.addEventListener("DOMContentLoaded", function () {
    // Wait for SVG to load if it's in an object tag
    const svgObject = document.querySelector(".architecture-svg");

    if (svgObject) {
        svgObject.addEventListener("load", function () {
            svgDoc = svgObject.contentDocument;
            initializeAnimations(svgObject);
            setupControlButtons();
        });
    }

    // Initialize tooltips for service cards if they exist
    initializeTooltips();
});

function setupControlButtons() {
    // Start animation button
    const startButton = document.getElementById("start-animation");
    if (startButton) {
        startButton.addEventListener("click", function () {
            if (!animationRunning) {
                animationRunning = true;
                startFullAnimation();
                this.textContent = "Pause Animation";
            } else {
                animationRunning = false;
                stopCurrentAnimation();
                this.textContent = "Start Animation";
            }
        });
    }

    // Frontend flow button
    const frontendButton = document.getElementById("show-frontend-flow");
    if (frontendButton) {
        frontendButton.addEventListener("click", function () {
            stopCurrentAnimation();
            showFrontendFlow();
        });
    }

    // Auth flow button
    const authButton = document.getElementById("show-auth-flow");
    if (authButton) {
        authButton.addEventListener("click", function () {
            stopCurrentAnimation();
            showAuthFlow();
        });
    }

    // Appointment flow button
    const appointmentButton = document.getElementById("show-appointment-flow");
    if (appointmentButton) {
        appointmentButton.addEventListener("click", function () {
            stopCurrentAnimation();
            showAppointmentFlow();
        });
    }

    // Reset button
    const resetButton = document.getElementById("reset-animation");
    if (resetButton) {
        resetButton.addEventListener("click", function () {
            stopCurrentAnimation();
            resetAllAnimations();
        });
    }
}

function initializeAnimations(svgObject) {
    // Get the SVG document
    if (!svgDoc) svgDoc = svgObject.contentDocument;
    if (!svgDoc) return;

    // Create data flow animations
    createDataFlowAnimations(svgDoc);

    // Add hover effects to services
    addServiceHoverEffects(svgDoc);

    // Add click events to show detailed flow
    addServiceClickEvents(svgDoc);

    // Setup tooltips for SVG elements
    setupSvgTooltips(svgDoc);
}

function createDataFlowAnimations(svgDoc) {
    // Define the main request flow paths
    const flowPaths = [
        // Frontend to API Gateway flow
        {
            id: "frontend-to-gateway",
            elements: ["frontend", "api-gateway"],
            color: "#3498db",
            duration: 2000,
            delay: 0,
        },
        // API Gateway to Auth Service flow
        {
            id: "gateway-to-auth",
            elements: ["api-gateway", "auth-service"],
            color: "#9b59b6",
            duration: 1500,
            delay: 2000,
        },
        // Auth Service to User DB flow
        {
            id: "auth-to-db",
            elements: ["auth-service", "databases"],
            color: "#2ecc71",
            duration: 1000,
            delay: 3500,
        },
        // API Gateway to Patient Service flow
        {
            id: "gateway-to-patient",
            elements: ["api-gateway", "patient-service"],
            color: "#9b59b6",
            duration: 1500,
            delay: 5000,
        },
        // Patient Service to Patient DB flow
        {
            id: "patient-to-db",
            elements: ["patient-service", "databases"],
            color: "#2ecc71",
            duration: 1000,
            delay: 6500,
        },
        // API Gateway to Doctor Service flow
        {
            id: "gateway-to-doctor",
            elements: ["api-gateway", "doctor-service"],
            color: "#9b59b6",
            duration: 1500,
            delay: 8000,
        },
        // Doctor Service to Doctor DB flow
        {
            id: "doctor-to-db",
            elements: ["doctor-service", "databases"],
            color: "#2ecc71",
            duration: 1000,
            delay: 9500,
        },
        // API Gateway to Appointment Service flow
        {
            id: "gateway-to-appointment",
            elements: ["api-gateway", "appointment-service"],
            color: "#9b59b6",
            duration: 1500,
            delay: 11000,
        },
        // Appointment Service to Appointment DB flow
        {
            id: "appointment-to-db",
            elements: ["appointment-service", "databases"],
            color: "#2ecc71",
            duration: 1000,
            delay: 12500,
        },
        // Appointment Service to Notification Service flow
        {
            id: "appointment-to-notification",
            elements: ["appointment-service", "notification-service"],
            color: "#e74c3c",
            duration: 1500,
            delay: 14000,
        },
        // Notification Service to Notification DB flow
        {
            id: "notification-to-db",
            elements: ["notification-service", "databases"],
            color: "#2ecc71",
            duration: 1000,
            delay: 15500,
        },
    ];

    // Store the flow paths globally for later use
    window.flowPaths = flowPaths;

    // Create and animate data packets for each flow path
    flowPaths.forEach((path) => {
        animateDataFlow(svgDoc, path);
    });
}

function animateDataFlow(svgDoc, flowPath) {
    const startElement = svgDoc.getElementById(flowPath.elements[0]);
    const endElement = svgDoc.getElementById(flowPath.elements[1]);

    if (!startElement || !endElement) return;

    // Get the bounding boxes of the elements
    const startRect = startElement.getBoundingClientRect();
    const endRect = endElement.getBoundingClientRect();

    // Create a data packet element
    const dataPacket = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "circle"
    );
    dataPacket.setAttribute("r", "5");
    dataPacket.setAttribute("fill", flowPath.color);
    dataPacket.setAttribute("filter", "url(#glow)");
    dataPacket.classList.add("data-packet");

    // Calculate start and end positions
    const startX = startRect.x + startRect.width / 2;
    const startY = startRect.y + startRect.height / 2;
    const endX = endRect.x + endRect.width / 2;
    const endY = endRect.y + endRect.height / 2;

    dataPacket.setAttribute("cx", startX);
    dataPacket.setAttribute("cy", startY);

    // Add the data packet to the SVG
    svgDoc.querySelector("svg").appendChild(dataPacket);

    // Animate the data packet
    const animationTimeout = setTimeout(() => {
        const animation = dataPacket.animate(
            [
                { cx: startX, cy: startY },
                { cx: endX, cy: endY },
            ],
            {
                duration: flowPath.duration,
                fill: "forwards",
            }
        );

        animation.onfinish = () => {
            // Create a pulse effect at the destination
            createPulseEffect(svgDoc, endX, endY, flowPath.color);

            // Remove the data packet after animation
            setTimeout(() => {
                dataPacket.remove();
            }, 500);
        };

        // Store the animation for potential cancellation
        dataPacket.animation = animation;
    }, flowPath.delay);

    // Store the timeout for potential cancellation
    dataPacket.timeout = animationTimeout;

    return dataPacket;
}

function createPulseEffect(svgDoc, x, y, color) {
    const pulse = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "circle"
    );
    pulse.setAttribute("cx", x);
    pulse.setAttribute("cy", y);
    pulse.setAttribute("r", "5");
    pulse.setAttribute("fill", color);
    pulse.setAttribute("opacity", "0.8");
    pulse.classList.add("pulse-effect");

    const svgElement = svgDoc.querySelector("svg");
    if (svgElement) {
        svgElement.appendChild(pulse);

        // Animate the pulse
        const animation = pulse.animate(
            [
                { r: 5, opacity: 0.8 },
                { r: 20, opacity: 0 },
            ],
            {
                duration: 1000,
                fill: "forwards",
                easing: "ease-out",
            }
        );

        animation.onfinish = () => {
            if (pulse.parentNode) {
                pulse.remove();
            }
        };

        return pulse;
    }
    return null;
}

function addServiceHoverEffects(svgDoc) {
    // Add hover effects to all service elements
    const serviceElements = [
        "frontend",
        "api-gateway",
        "auth-service",
        "patient-service",
        "doctor-service",
        "appointment-service",
        "notification-service",
        "rating-service",
        "infrastructure",
        "containerization",
        "databases",
    ];

    serviceElements.forEach((id) => {
        const element = svgDoc.getElementById(id);
        if (!element) return;

        element.style.cursor = "pointer";
        element.classList.add("service-node");

        element.addEventListener("mouseenter", () => {
            // Scale up the element slightly
            element.style.transform = "scale(1.05)";
            element.style.transition = "transform 0.3s ease";

            // Highlight connected services
            highlightConnectedServices(svgDoc, id, true);

            // Show tooltip
            showServiceTooltip(id, element);
        });

        element.addEventListener("mouseleave", () => {
            // Reset the element
            element.style.transform = "scale(1)";

            // Remove highlights
            highlightConnectedServices(svgDoc, id, false);

            // Hide tooltip
            hideServiceTooltip();
        });
    });
}

function highlightConnectedServices(svgDoc, serviceId, highlight) {
    // Define service connections based on the architecture
    const serviceConnections = {
        frontend: ["api-gateway"],
        "api-gateway": [
            "frontend",
            "auth-service",
            "patient-service",
            "doctor-service",
            "appointment-service",
            "notification-service",
            "rating-service",
        ],
        "auth-service": ["api-gateway", "databases"],
        "patient-service": ["api-gateway", "auth-service", "databases"],
        "doctor-service": ["api-gateway", "auth-service", "databases"],
        "appointment-service": [
            "api-gateway",
            "auth-service",
            "patient-service",
            "doctor-service",
            "notification-service",
            "databases",
        ],
        "notification-service": ["api-gateway", "auth-service", "databases"],
        "rating-service": [
            "api-gateway",
            "auth-service",
            "patient-service",
            "doctor-service",
            "databases",
        ],
    };

    // Get connected services
    const connectedServices = serviceConnections[serviceId] || [];

    // Highlight or reset connected services
    connectedServices.forEach((connectedId) => {
        const connectedElement = svgDoc.getElementById(connectedId);
        if (!connectedElement) return;

        if (highlight) {
            connectedElement.style.filter =
                "drop-shadow(0px 0px 8px rgba(52, 152, 219, 0.8))";
        } else {
            connectedElement.style.filter =
                "drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.1))";
        }
    });

    // Highlight or reset connection lines
    const lines = svgDoc.querySelectorAll("line");
    lines.forEach((line) => {
        // Check if this line connects the current service
        const connects = isLineConnectingService(
            line,
            serviceId,
            connectedServices
        );

        if (connects && highlight) {
            line.setAttribute("stroke-width", "3");
            line.setAttribute("stroke", "#3498db");
        } else if (connects) {
            line.setAttribute("stroke-width", "2");
            line.setAttribute("stroke", "#2c3e50");
        }
    });
}

function isLineConnectingService(line, serviceId, connectedServices) {
    // This is a simplified check - in a real implementation, you would need to
    // determine if a line actually connects two specific services
    if (!line.getAttribute("class")) return false;

    const lineClass = line.getAttribute("class");

    // Map service IDs to their corresponding parts in the class names
    const serviceIdMap = {
        frontend: "frontend",
        "api-gateway": "gateway",
        "auth-service": "auth",
        "patient-service": "patient",
        "doctor-service": "doctor",
        "appointment-service": "appointment",
        "notification-service": "notification",
        "rating-service": "rating",
        databases: "database",
    };

    // Get the mapped service ID
    const mappedServiceId = serviceIdMap[serviceId] || serviceId;

    return connectedServices.some((id) => {
        // Get the mapped connected service ID
        const mappedConnectedId = serviceIdMap[id] || id;

        // Check if the line class includes both service IDs
        return (
            lineClass.includes(mappedServiceId) &&
            lineClass.includes(mappedConnectedId)
        );
    });
}

function addServiceClickEvents(svgDoc) {
    // Add click events to show detailed flow for each service
    const serviceElements = [
        "frontend",
        "api-gateway",
        "auth-service",
        "patient-service",
        "doctor-service",
        "appointment-service",
        "notification-service",
        "rating-service",
    ];

    serviceElements.forEach((id) => {
        const element = svgDoc.getElementById(id);
        if (!element) return;

        element.addEventListener("click", () => {
            // Show a detailed flow animation for this service
            showDetailedServiceFlow(svgDoc, id);
        });
    });
}

// Function to start the full animation sequence
function startFullAnimation() {
    if (!svgDoc) return;

    // Reset any existing animations
    resetAllAnimations();

    // Create a sequence of animations
    const animationSequence = [
        // Frontend to API Gateway
        { from: "frontend", to: "api-gateway", color: "#3498db", delay: 0 },

        // API Gateway to Services
        {
            from: "api-gateway",
            to: "auth-service",
            color: "#9b59b6",
            delay: 1000,
        },
        {
            from: "api-gateway",
            to: "patient-service",
            color: "#9b59b6",
            delay: 1500,
        },
        {
            from: "api-gateway",
            to: "doctor-service",
            color: "#9b59b6",
            delay: 2000,
        },
        {
            from: "api-gateway",
            to: "appointment-service",
            color: "#9b59b6",
            delay: 2500,
        },
        {
            from: "api-gateway",
            to: "notification-service",
            color: "#9b59b6",
            delay: 3000,
        },
        {
            from: "api-gateway",
            to: "rating-service",
            color: "#9b59b6",
            delay: 3500,
        },

        // Services to Databases
        {
            from: "auth-service",
            to: "databases",
            color: "#2ecc71",
            delay: 4000,
        },
        {
            from: "patient-service",
            to: "databases",
            color: "#2ecc71",
            delay: 4500,
        },
        {
            from: "doctor-service",
            to: "databases",
            color: "#2ecc71",
            delay: 5000,
        },
        {
            from: "appointment-service",
            to: "databases",
            color: "#2ecc71",
            delay: 5500,
        },
        {
            from: "notification-service",
            to: "databases",
            color: "#2ecc71",
            delay: 6000,
        },
        {
            from: "rating-service",
            to: "databases",
            color: "#2ecc71",
            delay: 6500,
        },

        // Service Dependencies
        {
            from: "appointment-service",
            to: "notification-service",
            color: "#e74c3c",
            delay: 7000,
        },
        {
            from: "patient-service",
            to: "auth-service",
            color: "#e74c3c",
            delay: 7500,
        },
        {
            from: "doctor-service",
            to: "auth-service",
            color: "#e74c3c",
            delay: 8000,
        },
        {
            from: "appointment-service",
            to: "auth-service",
            color: "#e74c3c",
            delay: 8500,
        },
        {
            from: "rating-service",
            to: "patient-service",
            color: "#e74c3c",
            delay: 9000,
        },
        {
            from: "rating-service",
            to: "doctor-service",
            color: "#e74c3c",
            delay: 9500,
        },
    ];

    // Store all animation elements
    currentAnimation = {
        elements: [],
        timeouts: [],
    };

    // Start each animation in the sequence
    animationSequence.forEach((item) => {
        const timeout = setTimeout(() => {
            const element = animateServiceFlow(
                svgDoc,
                item.from,
                item.to,
                item.color
            );
            if (element) currentAnimation.elements.push(element);
        }, item.delay);

        currentAnimation.timeouts.push(timeout);
    });

    // Restart the animation after it completes
    const restartTimeout = setTimeout(() => {
        if (animationRunning) {
            startFullAnimation();
        }
    }, 12000); // Total duration plus buffer

    currentAnimation.timeouts.push(restartTimeout);
}

// Function to stop the current animation
function stopCurrentAnimation() {
    if (!currentAnimation) return;

    // Clear all timeouts
    currentAnimation.timeouts.forEach((timeout) => {
        clearTimeout(timeout);
    });

    // Cancel all animations and remove elements
    currentAnimation.elements.forEach((element) => {
        if (element.animation) {
            element.animation.cancel();
        }
        element.remove();
    });

    currentAnimation = null;
}

// Function to reset all animations
function resetAllAnimations() {
    // Stop any current animation
    stopCurrentAnimation();

    // Remove any remaining animation elements
    if (svgDoc) {
        const dataPackets = svgDoc.querySelectorAll(".data-packet");
        dataPackets.forEach((packet) => packet.remove());

        const pulseEffects = svgDoc.querySelectorAll(".pulse-effect");
        pulseEffects.forEach((pulse) => pulse.remove());
    }

    // Reset any highlighted elements
    resetHighlights();
}

// Function to reset highlights
function resetHighlights() {
    if (!svgDoc) return;

    // Reset service nodes
    const serviceNodes = svgDoc.querySelectorAll(".service-node");
    serviceNodes.forEach((node) => {
        node.style.transform = "scale(1)";
        node.style.filter = "drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.1))";
    });

    // Reset connection lines
    const lines = svgDoc.querySelectorAll("line");
    lines.forEach((line) => {
        line.setAttribute("stroke-width", "2");
        line.setAttribute("stroke", "#2c3e50");
    });
}

// Function to show frontend flow
function showFrontendFlow() {
    if (!svgDoc) return;

    // Reset animations
    resetAllAnimations();

    // Highlight frontend and API Gateway
    highlightService("frontend");
    highlightService("api-gateway");

    // Show animation from frontend to API Gateway
    animateServiceFlow(svgDoc, "frontend", "api-gateway", "#3498db");

    // After a delay, show API Gateway distributing to services
    setTimeout(() => {
        const services = [
            "auth-service",
            "patient-service",
            "doctor-service",
            "appointment-service",
            "notification-service",
            "rating-service",
        ];

        services.forEach((service, index) => {
            setTimeout(() => {
                animateServiceFlow(svgDoc, "api-gateway", service, "#9b59b6");
            }, index * 300);
        });
    }, 1000);
}

// Function to show authentication flow
function showAuthFlow() {
    if (!svgDoc) return;

    // Reset animations
    resetAllAnimations();

    // Highlight auth service
    highlightService("auth-service");

    // Show animation from API Gateway to Auth Service
    animateServiceFlow(svgDoc, "api-gateway", "auth-service", "#9b59b6");

    // After a delay, show Auth Service accessing database
    setTimeout(() => {
        animateServiceFlow(svgDoc, "auth-service", "databases", "#2ecc71");
    }, 1000);

    // After another delay, show services that depend on Auth
    setTimeout(() => {
        const services = [
            "patient-service",
            "doctor-service",
            "appointment-service",
            "notification-service",
            "rating-service",
        ];

        services.forEach((service, index) => {
            setTimeout(() => {
                animateServiceFlow(svgDoc, service, "auth-service", "#e74c3c");
            }, index * 300);
        });
    }, 2000);
}

// Function to show appointment flow
function showAppointmentFlow() {
    if (!svgDoc) return;

    // Reset animations
    resetAllAnimations();

    // Highlight appointment service
    highlightService("appointment-service");

    // Show animation from API Gateway to Appointment Service
    animateServiceFlow(svgDoc, "api-gateway", "appointment-service", "#9b59b6");

    // After a delay, show Appointment Service dependencies
    setTimeout(() => {
        // Appointment service accessing auth service
        animateServiceFlow(
            svgDoc,
            "appointment-service",
            "auth-service",
            "#e74c3c"
        );

        // After a delay, show patient and doctor service dependencies
        setTimeout(() => {
            animateServiceFlow(
                svgDoc,
                "appointment-service",
                "patient-service",
                "#e74c3c"
            );

            setTimeout(() => {
                animateServiceFlow(
                    svgDoc,
                    "appointment-service",
                    "doctor-service",
                    "#e74c3c"
                );

                // After another delay, show database access
                setTimeout(() => {
                    animateServiceFlow(
                        svgDoc,
                        "appointment-service",
                        "databases",
                        "#2ecc71"
                    );

                    // Finally, show notification being triggered
                    setTimeout(() => {
                        animateServiceFlow(
                            svgDoc,
                            "appointment-service",
                            "notification-service",
                            "#e74c3c"
                        );

                        // And notification service accessing its database
                        setTimeout(() => {
                            animateServiceFlow(
                                svgDoc,
                                "notification-service",
                                "databases",
                                "#2ecc71"
                            );
                        }, 1000);
                    }, 1000);
                }, 1000);
            }, 500);
        }, 500);
    }, 1000);
}

// Function to highlight a specific service
function highlightService(serviceId) {
    if (!svgDoc) return;

    const element = svgDoc.getElementById(serviceId);
    if (!element) return;

    element.classList.add("highlight-service");
}

// Function to setup tooltips for SVG elements
function setupSvgTooltips(svgDoc) {
    const serviceTooltip = document.getElementById("service-tooltip");
    if (!serviceTooltip) return;

    // Service descriptions for tooltips
    const serviceDescriptions = {
        frontend: {
            title: "Frontend Application",
            description:
                "React.js with Next.js providing a modern and responsive user interface with Material-UI components.",
        },
        "api-gateway": {
            title: "API Gateway",
            description:
                "Central entry point that routes requests to appropriate microservices, handles authentication, and provides load balancing.",
        },
        "auth-service": {
            title: "Authentication Service",
            description:
                "Handles user authentication, authorization, and security policy enforcement.",
        },
        "patient-service": {
            title: "Patient Service",
            description:
                "Manages patient profiles, medical history, and insurance information.",
        },
        "doctor-service": {
            title: "Doctor Service",
            description:
                "Handles doctor profiles, specialties, and credentials management.",
        },
        "appointment-service": {
            title: "Appointment Service",
            description:
                "Manages scheduling, booking, and real-time availability of appointments.",
        },
        "notification-service": {
            title: "Notification Service",
            description:
                "Handles email, SMS, and in-app notifications with delivery tracking.",
        },
        "rating-service": {
            title: "Rating Service",
            description:
                "Manages patient ratings, reviews, and analytics for doctors.",
        },
    };
}

// Function to show service tooltip
function showServiceTooltip(serviceId, element) {
    const serviceTooltip = document.getElementById("service-tooltip");
    if (!serviceTooltip) return;

    // Service descriptions for tooltips
    const serviceDescriptions = {
        frontend: {
            title: "Frontend Application",
            description:
                "React.js with Next.js providing a modern and responsive user interface with Material-UI components.",
        },
        "api-gateway": {
            title: "API Gateway",
            description:
                "Central entry point that routes requests to appropriate microservices, handles authentication, and provides load balancing.",
        },
        "auth-service": {
            title: "Authentication Service",
            description:
                "Handles user authentication, authorization, and security policy enforcement.",
        },
        "patient-service": {
            title: "Patient Service",
            description:
                "Manages patient profiles, medical history, and insurance information.",
        },
        "doctor-service": {
            title: "Doctor Service",
            description:
                "Handles doctor profiles, specialties, and credentials management.",
        },
        "appointment-service": {
            title: "Appointment Service",
            description:
                "Manages scheduling, booking, and real-time availability of appointments.",
        },
        "notification-service": {
            title: "Notification Service",
            description:
                "Handles email, SMS, and in-app notifications with delivery tracking.",
        },
        "rating-service": {
            title: "Rating Service",
            description:
                "Manages patient ratings, reviews, and analytics for doctors.",
        },
    };

    const serviceInfo = serviceDescriptions[serviceId];
    if (!serviceInfo) return;

    // Set tooltip content
    serviceTooltip.innerHTML = `
        <h4>${serviceInfo.title}</h4>
        <p>${serviceInfo.description}</p>
    `;

    // Position the tooltip near the element
    const rect = element.getBoundingClientRect();
    const svgRect = svgDoc.querySelector("svg").getBoundingClientRect();

    serviceTooltip.style.left = rect.left + rect.width / 2 - 125 + "px";
    serviceTooltip.style.top = rect.top - 80 + "px";

    // Show the tooltip
    serviceTooltip.classList.add("visible");
}

// Function to hide service tooltip
function hideServiceTooltip() {
    const serviceTooltip = document.getElementById("service-tooltip");
    if (!serviceTooltip) return;

    serviceTooltip.classList.remove("visible");
}

function showDetailedServiceFlow(svgDoc, serviceId) {
    // Define specific flows for each service
    const serviceFlows = {
        frontend: [{ from: "frontend", to: "api-gateway", color: "#3498db" }],
        "api-gateway": [
            { from: "api-gateway", to: "auth-service", color: "#9b59b6" },
            { from: "api-gateway", to: "patient-service", color: "#9b59b6" },
            { from: "api-gateway", to: "doctor-service", color: "#9b59b6" },
            {
                from: "api-gateway",
                to: "appointment-service",
                color: "#9b59b6",
            },
            {
                from: "api-gateway",
                to: "notification-service",
                color: "#9b59b6",
            },
            { from: "api-gateway", to: "rating-service", color: "#9b59b6" },
        ],
        "auth-service": [
            { from: "auth-service", to: "databases", color: "#2ecc71" },
        ],
        "patient-service": [
            { from: "patient-service", to: "auth-service", color: "#e74c3c" },
            { from: "patient-service", to: "databases", color: "#2ecc71" },
        ],
        "doctor-service": [
            { from: "doctor-service", to: "auth-service", color: "#e74c3c" },
            { from: "doctor-service", to: "databases", color: "#2ecc71" },
        ],
        "appointment-service": [
            {
                from: "appointment-service",
                to: "auth-service",
                color: "#e74c3c",
            },
            {
                from: "appointment-service",
                to: "patient-service",
                color: "#e74c3c",
            },
            {
                from: "appointment-service",
                to: "doctor-service",
                color: "#e74c3c",
            },
            {
                from: "appointment-service",
                to: "notification-service",
                color: "#e74c3c",
            },
            { from: "appointment-service", to: "databases", color: "#2ecc71" },
        ],
        "notification-service": [
            {
                from: "notification-service",
                to: "auth-service",
                color: "#e74c3c",
            },
            { from: "notification-service", to: "databases", color: "#2ecc71" },
        ],
        "rating-service": [
            { from: "rating-service", to: "auth-service", color: "#e74c3c" },
            { from: "rating-service", to: "patient-service", color: "#e74c3c" },
            { from: "rating-service", to: "doctor-service", color: "#e74c3c" },
            { from: "rating-service", to: "databases", color: "#2ecc71" },
        ],
    };

    // Get the flows for this service
    const flows = serviceFlows[serviceId] || [];

    // Animate each flow
    flows.forEach((flow, index) => {
        setTimeout(() => {
            animateServiceFlow(svgDoc, flow.from, flow.to, flow.color);
        }, index * 500);
    });
}

function animateServiceFlow(svgDoc, fromId, toId, color) {
    const fromElement = svgDoc.getElementById(fromId);
    const toElement = svgDoc.getElementById(toId);

    if (!fromElement || !toElement) return null;

    // Get the SVG's position for coordinate system adjustment
    const svgElement = svgDoc.querySelector("svg");
    const svgRect = svgElement.getBoundingClientRect();

    // Get the bounding boxes
    const fromRect = fromElement.getBoundingClientRect();
    const toRect = toElement.getBoundingClientRect();

    // Create a data packet
    const dataPacket = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "circle"
    );
    dataPacket.setAttribute("r", "6");
    dataPacket.setAttribute("fill", color);
    dataPacket.setAttribute("filter", "url(#glow)");
    dataPacket.classList.add("data-packet");

    // Calculate positions relative to the SVG coordinate system
    const fromX = fromRect.x - svgRect.x + fromRect.width / 2;
    const fromY = fromRect.y - svgRect.y + fromRect.height / 2;
    const toX = toRect.x - svgRect.x + toRect.width / 2;
    const toY = toRect.y - svgRect.y + toRect.height / 2;

    dataPacket.setAttribute("cx", fromX);
    dataPacket.setAttribute("cy", fromY);

    // Add to SVG
    svgElement.appendChild(dataPacket);

    // Animate
    const animation = dataPacket.animate(
        [
            { cx: fromX, cy: fromY },
            { cx: toX, cy: toY },
        ],
        {
            duration: 1000,
            fill: "forwards",
            easing: "ease-in-out",
        }
    );

    // Store the animation for potential cancellation
    dataPacket.animation = animation;

    animation.onfinish = () => {
        // Create pulse effect
        createPulseEffect(svgDoc, toX, toY, color);

        // Remove data packet
        setTimeout(() => {
            if (dataPacket.parentNode) {
                dataPacket.remove();
            }
        }, 500);
    };

    return dataPacket;
}

function initializeTooltips() {
    // Add tooltips to service cards if they exist
    const serviceCards = document.querySelectorAll(".service-card");

    serviceCards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
            const tooltip = card.querySelector(".tooltip");
            if (tooltip) {
                tooltip.style.display = "block";
                setTimeout(() => {
                    tooltip.style.opacity = "1";
                }, 10);
            }
        });

        card.addEventListener("mouseleave", () => {
            const tooltip = card.querySelector(".tooltip");
            if (tooltip) {
                tooltip.style.opacity = "0";
                setTimeout(() => {
                    tooltip.style.display = "none";
                }, 300);
            }
        });
    });
}
