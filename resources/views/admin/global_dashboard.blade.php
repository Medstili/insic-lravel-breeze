
 @extends('layouts.app')
    <!-- Main Content -->
    @section('content')
    
      <style>

      /* Main Content */
      /* .main-content {
        width: calc(100%-250px);
        /* margin:  auto; */
        /* background-color: red; */
        /* margin-left: 250px; */
      /* } */


      
      /* Stats Cards */
      .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
      }
      .stat-card:hover {
        transform: translateY(-5px);
      }
      .stat-value {
        color: #2c3e50;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .stat-trend {
        color: #777;
        font-size: 0.9rem;
      }
      
      /* Charts */
      .charts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      .chart-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .chart-header {
        margin-bottom: 20px;
      }
      .chart-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #2c3e50;
      }
      
      /* Appointment Table */
      .appointment-table {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .appointment-table h3 {
        margin-bottom: 15px;
        font-size: 1.2rem;
        color: #2c3e50;
      }
      table {
        width: 100%;
        border-collapse: collapse;
      }
      th {
        background: #2c3e50;
        color: #fff;
        padding: 12px 15px;
        text-align: left;
      }
      td {
        padding: 12px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #333;
      }
      .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: #e0e7ff;
        color: #4f46e5;
        display: inline-block;
      }
    </style>

  
        <!-- Stats Cards -->
        <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">24</div>
            <p>Active Coaches</p>
            <div class="stat-trend">↑ 12% from last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">158</div>
            <p>Registered Patients</p>
            <div class="stat-trend">↑ 8% from last week</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">56</div>
            <p>Appointments</p>
            <div class="stat-trend">↓ 3% cancellations</div>
        </div>
        </div>

        <!-- Interactive Charts -->
        <div class="charts-container">
        <div class="chart-box">
            <div class="chart-header">
            <h3>Specialty Distribution</h3>
            </div>
            <canvas id="specialtyChart"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-header">
            <h3>Appointment Status</h3>
            </div>
            <canvas id="statusChart"></canvas>
        </div>
        </div>

        <!-- Appointment Table -->
        <div class="appointment-table">
        <h3>Recent Appointments</h3>
        <table>
            <tr>
            <th>Patient</th>
            <th>Coach</th>
            <th>Date</th>
            <th>Status</th>
            </tr>
            <tr>
            <td>John Doe</td>
            <td>Dr. Smith</td>
            <td>2023-08-15</td>
            <td><span class="status-badge">Pending</span></td>
            </tr>
            <tr>
            <td>Sarah Johnson</td>
            <td>Coach Wilson</td>
            <td>2023-08-16</td>
            <td><span class="status-badge">Completed</span></td>
            </tr>
            <tr>
            <td>Mike Peters</td>
            <td>Dr. Anderson</td>
            <td>2023-08-17</td>
            <td><span class="status-badge">Canceled</span></td>
            </tr>
        </table>
        </div>


    <script>
 
      // Specialty Chart (Bar Chart)
    
    new Chart(document.getElementById('specialtyChart'), {
      type: 'bar',
      data: {
        labels: ['Nutrition', 'Fitness', 'Mental', 'Rehab', 'Yoga'],
        datasets: [{
          label: 'Appointments',
          data: [35, 28, 20, 12, 5],
          backgroundColor: [
            'rgba(44, 62, 80, 0.8)',
            'rgba(52, 73, 94, 0.8)',
            'rgba(74, 101, 114, 0.8)',
            'rgba(127, 140, 141, 0.8)',
            'rgba(189, 195, 199, 0.8)'
          ],
          borderColor: '#fff',
          borderWidth: 2,
          borderRadius: 12
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        }
      }
    });

    // Status Chart (Doughnut)
    new Chart(document.getElementById('statusChart'), {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Pending', 'Canceled'],
        datasets: [{
          data: [60, 25, 15],
          backgroundColor: [
            'rgba(44, 62, 80, 0.8)',
            'rgba(52, 73, 94, 0.8)',
            'rgba(74, 101, 114, 0.8)'
          ],
          borderColor: '#fff',
          borderWidth: 3
        }]
      },
      options: {
        cutout: '70%',
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });
    </script>
   

    @endsection
