/**
 * Dashboard Charts Only - Form Submission Data
 * This file only handles the two dashboard charts
 */

// Check if we're on dashboard page (only initialize if chart elements exist)
document.addEventListener('DOMContentLoaded', function () {
  console.log('Dashboard charts script loaded');

  // Only proceed if we're on a page with dashboard charts
  const hasAreaChart = document.querySelector("#apex_area1");
  const hasColumnChart = document.querySelector("#apex_column2");

  if (hasAreaChart || hasColumnChart) {
    console.log('Dashboard charts detected, waiting for ApexCharts...');

    // Wait for ApexCharts to load
    const checkApexCharts = setInterval(function () {
      if (typeof ApexCharts !== 'undefined') {
        clearInterval(checkApexCharts);
        console.log('ApexCharts loaded, initializing dashboard charts...');

        // Initialize charts if they have data
        if (window.dashboardChartData) {
          initializeDashboardCharts(window.dashboardChartData);
        }
      }
    }, 100);
  }
});

// Function to initialize dashboard charts
function initializeDashboardCharts(data) {
  console.log('Initializing dashboard charts with data:', data);

  // Area Chart
  if (document.querySelector("#apex_area1") && data.monthlyData) {
    var areaOptions = {
      series: [{
        name: 'Form Submissions',
        data: data.monthlyData
      }],
      chart: {
        height: 350,
        type: 'area',
        toolbar: { show: false }
      },
      colors: ["#22c55e"],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      xaxis: {
        categories: data.monthlyLabels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
      },
      yaxis: {
        title: { text: 'Submissions' }
      },
      title: {
        text: 'Monthly Form Submissions',
        align: 'left'
      },
      fill: {
        type: "gradient",
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          opacityTo: 0.3,
          stops: [0, 90, 100]
        }
      }
    };

    new ApexCharts(document.querySelector("#apex_area1"), areaOptions).render();
  }

  // Column Chart
  if (document.querySelector("#apex_column2") && data.topFormCounts) {
    var columnOptions = {
      chart: {
        height: 350,
        type: 'bar',
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: '55%',
        }
      },
      dataLabels: { enabled: false },
      colors: ["#2a77f4"],
      series: [{
        name: 'Submissions',
        data: data.topFormCounts
      }],
      xaxis: {
        categories: data.topFormNames || ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'],
        labels: {
          rotate: -45,
          rotateAlways: true
        }
      },
      yaxis: {
        title: { text: 'Submissions' }
      },
      title: {
        text: 'Top Forms by Submissions',
        align: 'left'
      }
    };

    new ApexCharts(document.querySelector("#apex_column2"), columnOptions).render();
  }
}

// Make function globally available
window.initializeDashboardCharts = initializeDashboardCharts;

// Also provide updateFormCharts for backward compatibility
window.updateFormCharts = function (labels, data, formNames, formCounts) {
  window.dashboardChartData = {
    monthlyLabels: labels,
    monthlyData: data,
    topFormNames: formNames,
    topFormCounts: formCounts
  };

  if (typeof ApexCharts !== 'undefined') {
    initializeDashboardCharts(window.dashboardChartData);
  }
};