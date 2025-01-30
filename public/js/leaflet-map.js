function openMapModal(cityName, zipcode) {
    document.getElementById("modalTitle").innerText = `Localisation de ${cityName}`;
    document.getElementById("mapModal").classList.remove("hidden");

    document.getElementById("mapContainer").innerHTML = '<div id="map" class="w-full h-full rounded-lg"></div>';

    const map = L.map('map').setView([48.8566, 2.3522], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(cityName + ' ' + zipcode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const { lat, lon } = data[0];
                map.setView([lat, lon], 12);

                L.marker([lat, lon]).addTo(map)
                    .bindPopup(`<b>${cityName}</b><br>Code Postal: ${zipcode}`)
                    .openPopup();
            } else {
                alert("Localisation introuvable !");
            }
        })
        .catch(error => {
            console.error("Erreur lors de la récupération des coordonnées :", error);
        });
}

function closeMapModal() {
    document.getElementById("mapModal").classList.add("hidden");
    document.getElementById("mapContainer").innerHTML = "";
}

function openMapModal(cityName, zipcode) {
    document.getElementById("modalTitle").innerText = `Localisation de ${cityName}`;
    document.getElementById("mapModal").classList.remove("hidden");

    document.getElementById("mapContainer").innerHTML = '<div id="map" class="w-full h-full rounded-lg"></div>';

    const map = L.map('map').setView([48.8566, 2.3522], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(cityName + ' ' + zipcode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const { lat, lon } = data[0];
                map.setView([lat, lon], 12);
                L.marker([lat, lon]).addTo(map)
                    .bindPopup(`<b>${cityName}</b><br>Code Postal: ${zipcode}`)
                    .openPopup();
            } else {
                alert("Localisation introuvable !");
            }
        })
        .catch(error => console.error("Erreur lors de la récupération des coordonnées :", error));
}

function closeMapModal() {
    document.getElementById("mapModal").classList.add("hidden");
    document.getElementById("mapContainer").innerHTML = "";
}

