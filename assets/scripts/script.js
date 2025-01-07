document.addEventListener('DOMContentLoaded', function() {
    // Define room prices
    const roomPrices = {
        '1': 1,   // Budget room
        '2': 2,  // Standard room
        '3': 3   // Luxury room
    };

    // Define feature prices
    const featurePrices = {
        '1': 2,    // Feature1
        '2': 1,    // Feature2
        '3': 2     // Feature3
    };

    // Select necessary elements
    const roomSelect = document.getElementById('hotel-rooms');
    const featureCheckboxes = document.querySelectorAll('input[name="feature_id[]"]');
    const totalCostDisplay = document.getElementById('total-cost');
    const checkInDateInput = document.getElementById('check-in-date');
    const checkOutDateInput = document.getElementById('check-out-date');

    // Function to calculate the number of days between two dates
    function calculateDaysBetween(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // Calculate the time difference in milliseconds
        const timeDifference = end.getTime() - start.getTime();
        
        // Convert milliseconds to days
        const daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1;
        
        return daysDifference;
    }

    // Function to calculate total cost
    function calculateTotalCost() {
        // Validate dates are selected
        if (!checkInDateInput.value || !checkOutDateInput.value) {
            totalCostDisplay.textContent = 'Total cost: Please select your dates';
            return;
        }

        // Calculate number of days
        const numberOfDays = calculateDaysBetween(checkInDateInput.value, checkOutDateInput.value);

        // Validate number of days (prevent negative or zero days)
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
        totalCostDisplay.textContent = `Total cost: $${totalCost} (${numberOfDays} nights)`;
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


function showCalendar() {
    const budgetRoom = document.getElementById("budget-room");
    const standardRoom = document.getElementById("standard-room");
    const luxuryRoom = document.getElementById("luxury-room");

    // Initially hide all rooms
    budgetRoom.style.display = 'none';
    standardRoom.style.display = 'none';
    luxuryRoom.style.display = 'none';

    //Create buttons in room view
    const budgetButton = document.getElementById("budget-button");
    const standardButton = document.getElementById("standard-button");
    const luxuryButton = document.getElementById("luxury-button");


    // Create buttons in calendar
    const budgetCalendarButton = document.getElementById("budget-calendar-button");
    const standardCalendarButton = document.getElementById("standard-calendar-button");
    const luxuryCalendarButton = document.getElementById("luxury-calendar-button");

    // Function to hide all rooms
    function hideAllCalendars() {
        budgetRoom.style.display = 'none';
        standardRoom.style.display = 'none';
        luxuryRoom.style.display = 'none';
    }

    // Add click event listeners to buttons for calendar
    budgetCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        budgetRoom.style.display = 'block';
    });

    standardCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        standardRoom.style.display = 'block';
    });

    luxuryCalendarButton.addEventListener('click', () => {
        hideAllCalendars();
        luxuryRoom.style.display = 'block';
    });


    // Add click event listeners to buttons from room
    budgetButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        budgetRoom.style.display = 'block';
    });

    standardButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        standardRoom.style.display = 'block';
    });

    luxuryButton.addEventListener('click', () => {
        hideAllCalendars();
        document.getElementById("calendar").scrollIntoView({behavior: 'smooth'});
        luxuryRoom.style.display = 'block';
    });


}

showCalendar();

function moveToBooking() {
    const roomSelect = document.getElementById('hotel-rooms');
    const budgetRoom = document.getElementById("budget-room");
    const standardRoom = document.getElementById("standard-room");
    const luxuryRoom = document.getElementById("luxury-room");

    function hideAllCalendars() {
        budgetRoom.style.display = 'none';
        standardRoom.style.display = 'none';
        luxuryRoom.style.display = 'none';
    }
    const budgetBookingButton = document.getElementById("budget-booking-button");
    budgetBookingButton.addEventListener('click', () => {
        roomSelect.value = '1';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        budgetRoom.style.display = 'block';
        
    })

    const standardBookingButton = document.getElementById("standard-booking-button");
    standardBookingButton.addEventListener('click', () => {
        roomSelect.value = '2';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        standardRoom.style.display = 'block';
        
    })

    const luxuryBookingButton = document.getElementById("luxury-booking-button");
    luxuryBookingButton.addEventListener('click', () => {
        roomSelect.value = '3';
        roomSelect.dispatchEvent(new Event('change'));
        document.getElementById("hotel-booking").scrollIntoView({behavior: 'smooth'});
        hideAllCalendars();
        luxuryRoom.style.display = 'block';
        
    })
}


moveToBooking();    