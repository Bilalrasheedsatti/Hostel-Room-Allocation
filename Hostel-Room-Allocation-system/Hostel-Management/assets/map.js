const buildingGradients = [
    "linear-gradient(to right, #e0eafc, #f7faff)",   // Light Blue/White
    "linear-gradient(to right, #fceabb, #fff)",      // Light Yellow/White
    "linear-gradient(to right, #e0c3fc, #fff)",      // Light Purple/White
    "linear-gradient(to right, #c2e9fb, #fff)",      // Light Aqua/White
    "linear-gradient(to right, #fbc2eb, #fff)"       // Light Pink/White
];

const floorGradients = [
    "linear-gradient(to right, #f5f7fa, #c3cfe2)",   // Grey/Blue
    "linear-gradient(to right, #fdfbfb, #ebedee)",   // White/Grey
    "linear-gradient(to right, #e0eafc, #cfdef3)",   // Light Blue
    "linear-gradient(to right, #f8ffae, #43c6ac)",   // Light Yellow/Teal
    "linear-gradient(to right, #fffde4, #005aa7)"    // Cream/Blue
];

const roomGradients = [
    "linear-gradient(to right, #e0eafc, #cfdef3)",   // Light Blue
    "linear-gradient(to right, #fbc2eb, #a6c1ee)",   // Light Pink/Lavender
    "linear-gradient(to right, #fdfbfb, #ebedee)",   // White/Grey
    "linear-gradient(to right, #c2e9fb, #81a4fd)",   // Aqua/Blue
    "linear-gradient(to right, #e0c3fc, #8ec5fc)",   // Light Purple/Blue
    "linear-gradient(to right, #fceabb, #f8b500)",   // Light Yellow/Orange
    "linear-gradient(to right, #f5f7fa, #c3cfe2)",   // Grey/Blue
    "linear-gradient(to right, #fffde4, #005aa7)",   // Cream/Blue
    "linear-gradient(to right, #f8ffae, #43c6ac)",   // Light Yellow/Teal
    "linear-gradient(to right, #e0eafc, #f7faff)"    // Light Blue/White
];

document.addEventListener('DOMContentLoaded', function() {
    fetch('api/get_map_data.php')
        .then(res => res.json())
        .then(data => renderMap(data));
});

function renderMap(data) {
    const map = document.getElementById('hostel-map');
    map.innerHTML = '';
    data.buildings.forEach((building, bIdx) => {
        const bDiv = document.createElement('div');
        bDiv.className = 'building';
        bDiv.style.background = buildingGradients[bIdx % buildingGradients.length];
        bDiv.innerHTML = `<div class="building-title">${building.name || "Empty Building"}</div>`;
        if (!building.floors || building.floors.length === 0) {
            // Default empty floor with 4 empty rooms
            bDiv.appendChild(createEmptyFloor(0));
        } else {
            building.floors.forEach((floor, fIdx) => {
                const fDiv = document.createElement('div');
                fDiv.className = 'floor';
                fDiv.style.background = floorGradients[fIdx % floorGradients.length];
                fDiv.innerHTML = `Floor ${floor.number || "Empty"}`;
                const roomsDiv = document.createElement('div');
                roomsDiv.className = 'rooms';
                if (!floor.rooms || floor.rooms.length === 0) {
                    // Default 4 empty rooms
                    for (let i = 0; i < 4; i++) {
                        const rDiv = document.createElement('div');
                        rDiv.className = 'room empty';
                        rDiv.textContent = '';
                        roomsDiv.appendChild(rDiv);
                    }
                } else {
                    floor.rooms.forEach((room, rIdx) => {
                        const rDiv = document.createElement('div');
                        rDiv.className = `room ${room.status}`;
                        rDiv.textContent = room.number || '';
                        rDiv.title = room.number ? `Room ${room.number}` : 'No Room';
                        // Each room on a floor gets a different color
                        rDiv.style.background = room.status === 'empty'
                            ? "linear-gradient(to right, #E0EAFC, #CFDEF3)"
                            : roomGradients[rIdx % roomGradients.length];
                        if (room.status !== 'empty') {
                            rDiv.onclick = () => showRoomInfo(room, building, floor);
                        }
                        roomsDiv.appendChild(rDiv);
                    });
                }
                fDiv.appendChild(roomsDiv);
                bDiv.appendChild(fDiv);
            });
        }
        map.appendChild(bDiv);
    });
}

function createEmptyFloor(floorNum) {
    const fDiv = document.createElement('div');
    fDiv.className = 'floor';
    fDiv.style.background = floorGradients[floorNum % floorGradients.length];
    fDiv.innerHTML = `Floor Empty`;
    const roomsDiv = document.createElement('div');
    roomsDiv.className = 'rooms';
    for (let i = 0; i < 4; i++) {
        const rDiv = document.createElement('div');
        rDiv.className = 'room empty';
        rDiv.textContent = '';
        roomsDiv.appendChild(rDiv);
    }
    fDiv.appendChild(roomsDiv);
    return fDiv;
}

function showRoomInfo(room, building, floor) {
    const info = document.getElementById('room-info');
    info.style.display = 'block';
    info.innerHTML = `
        <h3>Room ${room.number || "Empty"}</h3>
        <p><strong>Building:</strong> ${building.name || "Empty"}</p>
        <p><strong>Floor:</strong> ${floor.number || "Empty"}</p>
        <p><strong>Status:</strong> ${room.status ? room.status.charAt(0).toUpperCase() + room.status.slice(1) : "Empty"}</p>
        <p><strong>Capacity:</strong> ${room.capacity || "-"}</p>
        <p><strong>Occupied:</strong> ${room.occupied || "-"}</p>
        ${room.assigned_to ? `<p><strong>Assigned To:</strong> ${room.assigned_to}</p>` : ''}
    `;
}