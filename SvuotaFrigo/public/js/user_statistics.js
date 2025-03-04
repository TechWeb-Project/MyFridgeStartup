document.addEventListener('DOMContentLoaded', function() {
    // Check if user is premium before initializing charts
    if (!document.querySelector('.premium-card')) {
        // Initialize charts with empty data
        let recipesChart = null;
        let ingredientsChart = null;

        const initCharts = () => {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/user/statistics/data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);

                // Destroy existing charts if they exist
                if (recipesChart) recipesChart.destroy();
                if (ingredientsChart) ingredientsChart.destroy();

                // Initialize Recipes Chart
                const recipesCtx = document.getElementById('recipesChart').getContext('2d');
                recipesChart = new Chart(recipesCtx, {
                    type: 'pie',
                    data: {
                        labels: data.recipes.labels,
                        datasets: [{
                            data: data.recipes.values,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Le tue ricette più utilizzate'
                            }
                        }
                    }
                });

                // Initialize Ingredients Chart
                const ingredientsCtx = document.getElementById('ingredientsChart').getContext('2d');
                ingredientsChart = new Chart(ingredientsCtx, {
                    type: 'bar',
                    data: {
                        labels: data.ingredients.labels,
                        datasets: [{
                            label: 'Frequenza di utilizzo',
                            data: data.ingredients.values,
                            backgroundColor: '#4BC0C0',
                            borderColor: '#46a3a3',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Ingredienti più utilizzati'
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error details:', error);
                const charts = document.querySelectorAll('.card-body');
                charts.forEach(chart => {
                    chart.innerHTML = `<div class="alert alert-danger">
                        Errore nel caricamento delle statistiche: ${error.message || 'Unknown error'}
                    </div>`;
                });
            });
        };

        // Initial load of charts
        initCharts();

        // Refresh charts every 5 minutes
        setInterval(initCharts, 300000);
    }
});