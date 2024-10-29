// Open and close modals
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Chart Configurations
const rentalCtx = document.getElementById('rentalChart').getContext('2d');
new Chart(rentalCtx, {
    type: 'bar',
    data: { labels: ['Jan', 'Feb'], datasets: [{ data: [1200, 1500] }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
new Chart(maintenanceCtx, {
    type: 'pie',
    data: { labels: ['Completed', 'In Progress'], datasets: [{ data: [10, 5] }] },
    options: { responsive: true }
});
