const time = document.getElementById('time');
const date = document.getElementById('date');

const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

const interval = setInterval(() => {
    // Incrementar la hora del servidor en un segundo
    serverTime.setSeconds(serverTime.getSeconds() + 1);

    let day = serverTime.getDate(),
        month = serverTime.getMonth(),
        year = serverTime.getFullYear();

    time.innerHTML = serverTime.toLocaleTimeString();
    date.innerHTML = `${day} de ${monthNames[month]} del ${year}`;


}, 1000);
