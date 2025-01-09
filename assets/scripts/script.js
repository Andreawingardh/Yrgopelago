
/* Variables for calendars */
const budgetCalendar = document.getElementById("budget-calendar");
const standardCalendar = document.getElementById("standard-calendar");
const luxuryCalendar = document.getElementById("luxury-calendar");

//Variables for buttons in intro
const introAtmButton = document.getElementById("intro-atm-button");
const introBookNowButton = document.getElementById("intro-book-now-button");

//Variables for Check dates buttons from room view
const budgetCheckDateButton = document.getElementById("budget-check-date-button");
const standardCheckDateButton = document.getElementById("standard-check-date-button");
const luxuryCheckDateButton = document.getElementById("luxury-check-date-button");

// Variables for buttons in calendar
const budgetCalendarButton = document.getElementById("budget-calendar-button");
const standardCalendarButton = document.getElementById("standard-calendar-button");
const luxuryCalendarButton = document.getElementById("luxury-calendar-button");

// Variables for Book room buttons from room
const budgetBookRoomButton = document.getElementById("budget-book-now-button");
const standardBookRoomButton = document.getElementById("standard-book-now-button");
const luxuryBookRoomButton = document.getElementById("luxury-book-now-button");

//Variable for ATM view
const atm = document.getElementById("atm");

//Variable for hotel booking view
const hotelBooking = document.getElementById("hotel-booking");

//Variable for calendar view
const calendar = document.getElementById("calendar");
//Variables for the form
    // Room prices
    const roomPrices = {
        '1': 1,   // Budget room
        '2': 2,  // Standard room
        '3': 3   // Luxury room
    };

    // Feature prices
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
 
document.addEventListener('DOMContentLoaded', function() {

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


/* This function hides all calendars */
function hideAllCalendars() {
    budgetCalendar.style.display = 'none';
    standardCalendar.style.display = 'none';
    luxuryCalendar.style.display = 'none';
}


hideAllCalendars();
/* This function shows the different calendars when different buttons are clicked */
function showCalendar(roomCalendarButton, checkDateButton, roomCalendar) {

    // Click event listeners to buttons for calendar
    roomCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        roomCalendar.style.display = 'block';
    });

    // Click event listeners to buttons from room
    checkDateButton.addEventListener('click', () => {
        hideAllCalendars();
        calendar.scrollIntoView({behavior: 'smooth'});
        roomCalendar.style.display = 'block';
    });
}

// Event listeners for all room types
showCalendar(budgetCalendarButton, budgetCheckDateButton, budgetCalendar);
showCalendar(standardCalendarButton, standardCheckDateButton, standardCalendar);
showCalendar(luxuryCalendarButton, luxuryCheckDateButton, luxuryCalendar);


// This shows the calendar the selected room
roomSelect.addEventListener('change', () => {
    hideAllCalendars();
    const selectedValue = roomSelect.value;
    
    // Show appropriate calendar based on room
    if (selectedValue === '1') {
        budgetCalendar.style.display = 'block';
    } else if (selectedValue === '2') {
        standardCalendar.style.display = 'block';
    } else if (selectedValue === '3') {
        luxuryCalendar.style.display = 'block';
    }
});



/* This function moves to hotel-booking view when the Book room button is pressed for each room*/
function moveToBooking(roomCalendar, value) {
    hideAllCalendars();
        roomSelect.value = `${value}`;
        roomSelect.dispatchEvent(new Event('change'));
        hotelBooking.scrollIntoView({behavior: 'smooth'});
        roomCalendar.style.display = 'block';
}

//This adds event listeners to the different Book Room buttons
budgetBookRoomButton.addEventListener('click', () => moveToBooking(budgetCalendar, 1));
standardBookRoomButton.addEventListener('click', () => moveToBooking(standardCalendar, 2));
luxuryBookRoomButton.addEventListener('click', () => moveToBooking(luxuryCalendar, 3));

introAtmButton.addEventListener('click', () => {
    atm.scrollIntoView({behavior: 'smooth'});
})

introBookNowButton.addEventListener('click', () => {
    hotelBooking.scrollIntoView({behavior: 'smooth'});
})