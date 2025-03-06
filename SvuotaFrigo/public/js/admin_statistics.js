document.addEventListener('DOMContentLoaded', function() {
    // Utility function for chart colors
    const getChartColors = () => ({
        red: 'rgba(255, 99, 132, 0.2)',
        redBorder: 'rgba(255, 99, 132, 1)',
        green: 'rgba(75, 192, 192, 0.2)',
        greenBorder: 'rgba(75, 192, 192, 1)',
        blue: 'rgba(54, 162, 235, 0.2)',
        blueBorder: 'rgba(54, 162, 235, 1)'
    });

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Initialize Error Chart
    const initErrorChart = (labels, data) => {
        const ctx = document.getElementById('errorChart').getContext('2d');
        const colors = getChartColors();
        
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Errori Giornalieri',
                    data: data,
                    backgroundColor: colors.red,
                    borderColor: colors.redBorder,
                    borderWidth: 1,
                    tension: 0.4
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    title: {
                        display: true,
                        text: 'Trend Errori Ultimi 7 Giorni'
                    }
                }
            }
        });
    };

    // Initialize Recipe Chart
    const initRecipeChart = (labels, data) => {
        const ctx = document.getElementById('recipeChart').getContext('2d');
        const colors = getChartColors();
        
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ricette Generate',
                    data: data,
                    backgroundColor: colors.green,
                    borderColor: colors.greenBorder,
                    borderWidth: 1
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    title: {
                        display: true,
                        text: 'Generazione Ricette Ultimi 7 Giorni'
                    }
                }
            }
        });
    };

    // Initialize AI Performance Chart
    const initAIPerformanceChart = (labels, data) => {
        const ctx = document.getElementById('aiPerformanceChart').getContext('2d');
        const colors = getChartColors();
        
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tempo Generazione (s)',
                        data: data.map(d => d.generation_time),
                        backgroundColor: colors.blue,
                        borderColor: colors.blueBorder,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tasso di Successo (%)',
                        data: data.map(d => d.success_rate),
                        backgroundColor: colors.green,
                        borderColor: colors.greenBorder,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Utilizzo CPU (%)',
                        data: data.map(d => d.cpu_usage),
                        backgroundColor: colors.red,
                        borderColor: colors.redBorder,
                        borderWidth: 1,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Utilizzo Memoria (MB)',
                        data: data.map(d => d.memory_usage),
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Tempo (s)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Percentuale (%)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performance IA'
                    }
                }
            }
        });
    };

    // Function to update charts with new data
    const updateCharts = () => {
        fetch('/admin/statistics/data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update charts with new data
            if (window.errorChart) window.errorChart.destroy();
            if (window.recipeChart) window.recipeChart.destroy();
            if (window.aiPerformanceChart) window.aiPerformanceChart.destroy();

            window.errorChart = initErrorChart(data.errorLabels, data.errorData);
            window.recipeChart = initRecipeChart(data.recipeLabels, data.recipeData);
            window.aiPerformanceChart = initAIPerformanceChart(data.aiPerformanceLabels, data.aiPerformanceData);

            // Update statistics
            document.querySelector('#todayErrors').textContent = data.todayErrors;
            document.querySelector('#avgDailyRecipes').textContent = data.avgDailyRecipes;
            document.querySelector('#acceptanceRate').textContent = data.acceptanceRate + '%';
            document.querySelector('#avgGenerationTime').textContent = data.avgGenerationTime + 's';
            document.querySelector('#successRate').textContent = data.successRate + '%';
            document.querySelector('#avgCpuUsage').textContent = data.avgCpuUsage + '%';
            document.querySelector('#avgMemoryUsage').textContent = data.avgMemoryUsage + ' MB';
        })
        .catch(error => console.error('Error fetching statistics:', error));
    };

    // Initial charts setup
    updateCharts();

    // Update charts every 5 minutes
    setInterval(updateCharts, 300000);
});