document.addEventListener('DOMContentLoaded', function () {
    const calendarTable = document.getElementById('calendar');
    const currentMonthYearElement = document.getElementById('current-month-year');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    const addEventButton = document.getElementById('add-event');
    const addEventForm = document.getElementById('add-event-form');
    const eventFormElements = addEventForm.elements;
    const deleteEventButton = document.getElementById('deleteEvent');
    const csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";
    console.log(csrfToken);

    let currentDate = new Date();
    let eventData = [];
    let selectedDate = new Date();

    function updateCalendarWithData(year, month) {
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();
        const lastDay =  new Date(year, month, daysInMonth).getDay();

        currentMonthYearElement.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;

        let date = 1;
        const calendarBody = calendarTable.querySelector('tbody');
        calendarBody.innerHTML = '';

        for (let week = 0; date <= daysInMonth; week++) {
            const row = document.createElement('tr');
            for (let day = 0; day < 7; day++) {
                const cell = document.createElement('td');

                if (week === 0 && day < firstDay) {
                    cell.textContent = '';
                } else if (week === (Math.ceil((daysInMonth + firstDay) / 7) - 1) && day > lastDay) {
                    cell.textContent = '';
                } else {
                    const dayEvents = eventData.filter(event => {
                        const eventDate = new Date(event.event_datetime);
                        return eventDate.getFullYear() === year && eventDate.getMonth() === month && eventDate.getDate() === date;
                    });

                    const btn = document.createElement('button');
                    btn.textContent = `${date} (${dayEvents.length} events)`;

                    (function (y, m, d) {
                        btn.addEventListener('click', function () {
                            console.log("Button clicked for date:", d);
                            displayEventsForDate(y, m, d);
                        });
                    })(year, month, date);

                    cell.appendChild(btn);
                    date++;
                }

                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
        }
    }

    function deleteEvent() {
        const eventName = prompt('Enter the name of the event you want to delete:');

        if (eventName === null) {
            return;
        }

        if (!eventName.trim()) {
            alert('Empty event name');
            return;
        }

        const confirmDelete = confirm(`Are you sure you want to delete the event: "${eventName}"?`);

        if (confirmDelete) {
            $.ajax({
                url: 'delete_event.php',
                method: 'POST',
                data: JSON.stringify({ eventName: eventName }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Event deleted successfully:', response.message);
                        fetchEventData();
                        const currentlySelectedYear = selectedDate.getFullYear();
                        const currentlySelectedMonth = selectedDate.getMonth();
                        const currentlySelectedDay = selectedDate.getDate();
                        displayEventsForDate(currentlySelectedYear, currentlySelectedMonth, currentlySelectedDay);
                    } else {
                        console.error('Error deleting event:', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request error:', status + ': ' + error);
                }
            });
        }
    }

    function editEvent() {
        const eventName = prompt('Enter the name of the event you want to edit:');

        if (eventName === null || !eventName.trim()) {
            alert('Event name cannot be empty.');
            return;
        }

        const eventDataToEdit = eventData.find(event => event.event_name === eventName);

        if (!eventDataToEdit) {
            alert(`Event "${eventName}" not found.`);
            return;
        }
        const confirmEdit = confirm(`Are you sure you want to edit the event: "${eventName}"?`);

        if (confirmEdit) {
            const newEventName = prompt('Edit Event Name:', eventDataToEdit.event_name) || eventDataToEdit.event_name;
            const newGroupName = prompt('Edit Group Name:', eventDataToEdit.group_name) || eventDataToEdit.group_name;
            const newEventDatetime = prompt('Edit Event Datetime (YYYY-MM-DD HH:MM):', eventDataToEdit.event_datetime) || eventDataToEdit.event_datetime;

            $.ajax({
                url: 'edit_events.php',
                method: 'POST',
                data: JSON.stringify({
                    eventName: eventName,
                    newEventName: newEventName,
                    newGroupName: newGroupName,
                    newEventDatetime: newEventDatetime
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Event edited successfully:', response.message);
                        fetchEventData();
                        displayEventsForDate(selectedDate.getFullYear(), selectedDate.getMonth(), selectedDate.getDate());
                    } else {
                        console.error('Error editing event:', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request error:', status + ': ' + error);
                }
            });
        }
    }

    function fetchEventData() {
        $.ajax({
            url: 'get_events.php',
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                eventData = response;
                console.log(eventData);
                updateCalendarWithData(currentDate.getFullYear(), currentDate.getMonth());
                displayUpcomingEvents();
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', status + ': ' + error);
            }
        });
    }

    function displayGroupEvents(groupName) {
        
        $.ajax({
            url: 'display_event_group.php',
            method: 'POST',
            data: { groupName: groupName },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    console.log('Error: ' + response.error);
                    return;
                }
                displayEvents(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', status + ': ' + error);
            }
        });
        function displayEvents(events) {
            const eventsContainer = document.getElementById('event-display-container');
            eventsContainer.innerHTML = '';
            const titleElement = document.createElement('p');
            titleElement.innerHTML = '<strong>Group Events</strong>';
            eventsContainer.appendChild(titleElement);
        
            events.forEach(event => {
                const eventElement = document.createElement('p');
                eventElement.textContent = `Event Name: ${event.event_name}, Group Name: ${event.group_name}, Event Datetime: ${new Date(event.event_datetime).toLocaleString()}`;
                eventsContainer.appendChild(eventElement);
            });
        }
    }

    function displayEventsForDate(year, month, day) {
        const eventsForDay = eventData.filter(event => {
            const eventDate = new Date(event.event_datetime);
            return eventDate.getFullYear() === year &&
                eventDate.getMonth() === month &&
                eventDate.getDate() === day;
        });

        const displayDiv = document.getElementById('events-display');
        displayDiv.innerHTML = '';
        eventsForDay.forEach(event => {
            const eventElement = document.createElement('p');
            const eventDetails = `Event Name: ${event.event_name}, Group Name: ${event.group_name}, Event Datetime: ${new Date(event.event_datetime).toLocaleString()}`;
            eventElement.textContent = eventDetails;
            displayDiv.appendChild(eventElement);
            console.log('Event Details:', eventDetails);
        });
    }

    function displayUpcomingEvents() {
        const cutoffTime = new Date();
        cutoffTime.setHours(cutoffTime.getHours() + 48);

        const upcomingEvents = eventData.filter(event => {
            const eventDate = new Date(event.event_datetime);
            return eventDate >= new Date() && eventDate <= cutoffTime;
        });

        const displayDiv = document.getElementById('events-display2');
        displayDiv.innerHTML = '<h2>Upcoming Events</h2>';
        upcomingEvents.forEach(event => {
            const eventElement = document.createElement('p');
            const eventDetails = `Event Name: ${event.event_name}, Group Name: ${event.group_name}, Event Datetime: ${new Date(event.event_datetime).toLocaleString()}`;
            eventElement.textContent = eventDetails;
            displayDiv.appendChild(eventElement);
        });
    }

       function displayUpcomingEvents() {
        const cutoffTime = new Date();
        cutoffTime.setHours(cutoffTime.getHours() + 48);

        const upcomingEvents = eventData.filter(event => {
            const eventDate = new Date(event.event_datetime);
            return eventDate >= new Date() && eventDate <= cutoffTime;
        });

        const displayDiv = document.getElementById('events-display2');
        displayDiv.innerHTML = '<h2>Upcoming Events</h2>';
        upcomingEvents.forEach(event => {
            const eventElement = document.createElement('p');
            const eventDetails = `Event Name: ${event.event_name}, Group Name: ${event.group_name}, Event Datetime: ${new Date(event.event_datetime).toLocaleString()}`;
            eventElement.textContent = eventDetails;
            displayDiv.appendChild(eventElement);
        });
    }

function showSharedEvents() {
    $.ajax({
        url: 'show_shared_events.php', 
        method: 'POST',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            console.log(response.data); 
            

            const sharedEventsContainer = document.getElementById('events-shared-display');
            sharedEventsContainer.innerHTML = ''; 

            if (response.length === 0) {
              
                const messageElement = document.createElement('p');
                messageElement.textContent = 'You have no shared events.';
                sharedEventsContainer.appendChild(messageElement);
            } else {

                const labelElement = document.createElement('strong');
                labelElement.textContent = 'Shared Events';
                sharedEventsContainer.appendChild(labelElement);

        
                response.forEach(event => {
                    const eventElement = document.createElement('p');
                    eventElement.innerHTML = `Event Name: ${event.event_name}, Group Name: ${event.group_name}, Event Datetime: ${event.event_datetime}`;
                    sharedEventsContainer.appendChild(eventElement);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX request error:', status + ': ' + error);
        }
    });
}


    function shareEvent() {
        const eventName = prompt('Enter the name of the event you want to share:');
        if (!eventName) {
            return;
        }
    
        const otherUsername = prompt('Enter the username of the user you want to share with:');
        if (!otherUsername) {
            return; 
        }
    
     
        $.ajax({
            url: 'shareEvent.php', 
            method: 'POST',
            data: JSON.stringify({
                eventName: eventName,
                otherUsername: otherUsername
            }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Event shared successfully');
                } else {
                    alert('Error sharing event: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', status + ': ' + error);
            }
        });
    }

    fetchEventData();
    showSharedEvents();

    $('#groupEvent').click(function () {
        const groupName = prompt('Enter the group name to sort events by:');
        if (groupName) {
            displayGroupEvents(groupName);
        }
    });

    deleteEventButton.addEventListener('click', () => {
        deleteEvent();
    });

    nextMonthButton.addEventListener('click', () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
        updateCalendarWithData(currentDate.getFullYear(), currentDate.getMonth());
    });

    prevMonthButton.addEventListener('click', () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        updateCalendarWithData(currentDate.getFullYear(), currentDate.getMonth());
    });

    addEventButton.addEventListener('click', () => {
        addEventForm.style.display = 'block';
    });

    const editEventButton = document.getElementById('editEvent');
    editEventButton.addEventListener('click', () => {
        editEvent();
    });

    const shareEventButton = document.getElementById('share-event-button');
    shareEventButton.addEventListener('click', () => {
        shareEvent();
        showSharedEvents();
    });
    


    addEventForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const eventHour = eventFormElements['event-time-hour'].value.padStart(2, '0');
        const eventMinute = eventFormElements['event-time-minute'].value.padStart(2, '0');
        const fullDatetime = `${eventFormElements['event-date'].value} ${eventHour}:${eventMinute}:00`;

        const eventData = {
            eventName: eventFormElements['event-name'].value,
            groupName: eventFormElements['group-name'].value,
            eventDatetime: fullDatetime,
            csrf_token: csrfToken,
        };

        $.ajax({
            url: 'add_event.php',
            method: 'POST',
            data: JSON.stringify(eventData),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    console.log('Event added successfully:', response.message);
                    addEventForm.style.display = 'none';
                    addEventForm.reset();
                    fetchEventData();
                } else {
                    console.error('Error adding event:', response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', status + ': ' + error);
            }
        });
    });
});
