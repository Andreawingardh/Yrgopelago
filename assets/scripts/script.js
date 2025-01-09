
/* Variables for */
const budgetCalendar = document.getElementById("budget-room");
const standardCalendar = document.getElementById("standard-room");
const luxuryCalendar = document.getElementById("luxury-room");




document.addEventListener('DOMContentLoaded', function() {
    // Define room prices
    const roomPrices = {
        '1': 1,   // Budget room
        '2': 2,  // Standard room
        '3': 3   // Luxury room
    };

    // This defines feature prices
    const featurePrices = {
        '1': 2,    // Ping pong table
        '2': 1,    // Yatzy
        '3': 2     // Unicycle
    };

    // Select necessary elements
    const roomSelect = document.getElementById('hotel-rooms');
    const featureCheckboxes = document.querySelectorAll('input[name="feature_id[]"]');
    const totalCostDisplay = document.getElementById('total-cost');
    const checkInDateInput = document.getElementById('check-in-date');
    const checkOutDateInput = document.getElementById('check-out-date');

    // This calculates the number of days between two dates
    function calculateDaysBetween(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // This calculate the time difference
        const timeDifference = end.getTime() - start.getTime();
        
        // This converts to days
        const daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1;
        
        return daysDifference;
    }

    // This function calculates total cost dynamically as user fills in form
    function calculateTotalCost() {
        // Validate dates are selected
        if (!checkInDateInput.value || !checkOutDateInput.value) {
            totalCostDisplay.textContent = 'Total cost: Please select your dates';
            return;
        }

        // Calculate number of days
        const numberOfDays = calculateDaysBetween(checkInDateInput.value, checkOutDateInput.value);

        // Validate number of days (prevent negative days)
        if (numberOfDays <= 0) {
            totalCostDisplay.textContent = 'Total cost: Oops! Those days don\'t seem to line up.';
            return;
        }

        // Get selected room price
        const selectedRoomPrice = roomPrices[roomSelect.value] || 0;

        // Calculate feature prices
        let featureCost = 0;
        featureCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                featureCost += featurePrices[checkbox.value] || 0;
            }
        });

        // Calculate total cost (room price * number of days + feature costs)
        const totalCost = (selectedRoomPrice * numberOfDays) + (featureCost);

        // Update total cost display
        totalCostDisplay.textContent = `Total cost: $${totalCost} (${numberOfDays} days)`;
    }

    // Add event listeners
    roomSelect.addEventListener('change', calculateTotalCost);
    
    featureCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotalCost);
    });

    checkInDateInput.addEventListener('change', calculateTotalCost);
    checkOutDateInput.addEventListener('change', calculateTotalCost);

    // Initial calculation
    calculateTotalCost();
});


/* This function hides all calendars before displaying them */
function hideAllCalendars() {
    budgetCalendar.style.display = 'none';
    standardCalendar.style.display = 'none';
    luxuryCalendar.style.display = 'none';
}

/* This function shows the different calendars when different buttons are clicked and the rooms selected in the dropdown menu in the form. */

function showCalendar() {

    //This creates variables for room calendars
    const budgetCalendar = document.getElementById("budget-room");
    const standardCalendar = document.getElementById("standard-room");
    const luxuryCalendar = document.getElementById("luxury-room");

    // Initially hide all rooms
    hideAllCalendars();

    //Create buttons in room view
    const budgetButton = document.getElementById("budget-button");
    const standardButton = document.getElementById("standard-button");
    const luxuryButton = document.getElementById("luxury-button");


    // Create buttons in calendar
    const budgetCalendarButton = document.getElementById("budget-calendar-button");
    const standardCalendarButton = document.getElementById("standard-calendar-button");
    const luxuryCalendarButton = document.getElementById("luxury-calendar-button");

    //Creates variable for select room
    const roomSelect = document.getElementById('hotel-rooms');

    // Function to hide all rooms
    // function hideAllCalendars() {
    //     budgetCalendar.style.display = 'none';
    //     standardCalendar.style.display = 'none';
    //     luxuryCalendar.style.display = 'none';
    // }

    // Add click event listeners to buttons for calendar
    budgetCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        budgetCalendar.style.display = 'block';
    });

    standardCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        standardCalendar.style.display = 'block';
    });

    luxuryCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        luxuryCalendar.style.display = 'block';
    });


    // Add click event listeners to buttons from room
    budgetButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        budgetCalendar.style.display = 'block';
    });

    standardButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        standardCalendar.style.display = 'block';
    });

    luxuryButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        luxuryCalendar.style.display = 'block';
    });

    //This shows calendars on change of rooms in dropdown menu
    roomSelect.addEventListener('change', () => {
        hideAllCalendars();
        if(roomSelect.value == '1') {
            hideAllCalendars();
            budgetCalendar.style.display = 'block';
        } 
        if (roomSelect.value == '2') {
            hideAllCalendars();
            standardCalendar.style.display = 'block';
        } 
        
        if (roomSelect.value == '3') {
            hideAllCalendars();
            luxuryCalendar.style.display = 'block';
        }
    })

}

showCalendar();

function moveToBooking() {
    const roomSelect = document.getElementById('hotel-rooms');
    const budgetCalendar = document.getElementById("budget-room");
    const standardCalendar = document.getElementById("standard-room");
    const luxuryCalendar = document.getElementById("luxury-room");


    const budgetBookingButton = document.getElementById("budget-booking-button");
    budgetBookingButton.addEventListener('click', () => {
        roomSelect.value = '1';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        budgetCalendar.style.display = 'block';
        
    })

    const standardBookingButton = document.getElementById("standard-booking-button");
    standardBookingButton.addEventListener('click', () => {
        roomSelect.value = '2';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        standardCalendar.style.display = 'block';
        
    })

    const luxuryBookingButton = document.getElementById("luxury-booking-button");
    luxuryBookingButton.addEventListener('click', () => {
        roomSelect.value = '3';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        luxuryCalendar.style.display = 'block';
        
    })
}

moveToBooking();    
