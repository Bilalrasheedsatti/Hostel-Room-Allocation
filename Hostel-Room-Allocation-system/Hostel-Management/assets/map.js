document.addEventListener('DOMContentLoaded', function() {
    const hostelMap = document.getElementById('hostel-map');
    const roomInfo = document.getElementById('room-info');
    
    // Sample room data - in a real app, this would come from the database
    const rooms = [
        { id: 101, building: 'A', floor: 1, capacity: 2, occupied: 1, status: 'available' },
        { id: 102, building: 'A', floor: 1, capacity: 2, occupied: 2, status: 'occupied' },
        { id: 103, building: 'A', floor: 1, capacity: 2, occupied: 0, status: 'maintenance' },
        // Add more rooms as needed
    ];
    
    // Create the map
    function createMap() {
        // This is a simplified representation
        // In a real app, you might use SVG or Canvas for more complex maps
        
        const buildingA = document.createElement('div');
        buildingA.className = 'building';
        buildingA.innerHTML = '<h3>Building A</h3>';
        
        const floorsContainer = document.createElement('div');
        floorsContainer.className = 'floors';
        
        // Create 3 floors for the example
        for (let floor = 1; floor <= 3; floor++) {
            const floorDiv = document.createElement('div');
            floorDiv.className = 'floor';
            floorDiv.innerHTML = `<h4>Floor ${floor}</h4>`;
            
            const roomsContainer = document.createElement('div');
            roomsContainer.className = 'rooms';
            
            // Add rooms for this floor
            const floorRooms = rooms.filter(room => room.floor === floor);
            floorRooms.forEach(room => {
                const roomDiv = document.createElement('div');
                roomDiv.className = `room ${room.status}`;
                roomDiv.textContent = room.id;
                roomDiv.dataset.roomId = room.id;
                
                roomDiv.addEventListener('click', function() {
                    showRoomInfo(room);
                });
                
                roomsContainer.appendChild(roomDiv);
            });
            
            floorDiv.appendChild(roomsContainer);
            floorsContainer.appendChild(floorDiv);
        }
        
        buildingA.appendChild(floorsContainer);
        hostelMap.appendChild(buildingA);
    }
    
    // Show room information when clicked
    function showRoomInfo(room) {
        let statusClass;
        let statusText;
        
        switch(room.status) {
            case 'available':
                statusClass = 'available';
                statusText = 'Available';
                break;
            case 'occupied':
                statusClass = 'occupied';
                statusText = 'Occupied';
                break;
            case 'maintenance':
                statusClass = 'maintenance';
                statusText = 'Under Maintenance';
                break;
        }
        
        roomInfo.innerHTML = `
            <h3>Room ${room.id}</h3>
            <p>Building: ${room.building}</p>
            <p>Floor: ${room.floor}</p>
            <p>Capacity: ${room.capacity}</p>
            <p>Occupied: ${room.occupied}</p>
            <p>Status: <span class="${statusClass}">${statusText}</span></p>
        `;
        
        roomInfo.style.display = 'block';
    }
    
    // Initialize the map
    createMap();
});