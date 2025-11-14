# Multi-Role Dashboard System

This directory contains a multi-role dashboard system built with Metronic template for Admin, Instructor, and Student roles.

## 📁 Folder Structure

```
src/
├── admin/
│   ├── dashboard/
│   │   ├── index.html                    # Admin dashboard main page
│   │   ├── components/
│   │   │   ├── kpi-summary.html          # KPI summary widget
│   │   │   ├── income-chart.html         # 12-month income chart
│   │   │   ├── latest-registrations.html # Latest 10 registrations
│   │   │   ├── reschedule-requests.html  # Pending reschedule requests
│   │   │   ├── today-sessions.html       # Today's sessions
│   │   │   ├── latest-tickets.html       # Latest tickets/chat
│   │   └── partials/
│   │       ├── _scripts.js               # Admin dashboard scripts
│   │       └── _styles.css               # Admin dashboard styles
│   └── quick-actions.html                # Admin quick actions page
│
├── instructor/
│   ├── dashboard/
│   │   ├── index.html                    # Instructor dashboard main page
│   │   ├── components/
│   │   │   ├── today-sessions.html       # Today & tomorrow sessions
│   │   │   ├── my-courses.html           # Courses I'm teaching
│   │   │   ├── reschedule-pending.html   # Pending reschedule requests
│   │   │   ├── attendance-pending.html   # Pending attendance input
│   │   │   ├── latest-messages.html      # Latest messages from students
│   │   └── partials/
│   │       ├── _scripts.js               # Instructor dashboard scripts
│   │       └── _styles.css               # Instructor dashboard styles
│   └── quick-actions.html                # Instructor quick actions page
│
├── student/
│   ├── dashboard/
│   │   ├── index.html                    # Student dashboard main page
│   │   ├── components/
│   │   │   ├── continue-learning.html    # Continue learning widget
│   │   │   ├── next-session.html         # Next session widget
│   │   │   ├── active-days-counter.html  # Active days counter
│   │   │   ├── payment-status.html       # Payment status widget
│   │   │   ├── certificate-ready.html    # Ready certificates
│   │   │   ├── chat-shortcut.html        # Chat with instructor/admin
│   │   └── partials/
│   │       ├── _scripts.js               # Student dashboard scripts
│   │       └── _styles.css               # Student dashboard styles
│   └── quick-actions.html                # Student quick actions page
│
└── shared/
    ├── layout/                           # Shared layout files (to be created)
    │   ├── header.html                   # Shared header
    │   ├── sidebar.html                  # Shared sidebar
    │   └── footer.html                   # Shared footer
    ├── utils/
    │   ├── chart-config.js               # Chart configurations
    │   └── formatters.js                 # Formatting utilities
    └── styles/
        └── global.css                    # Global shared styles
```

## 🎯 Dashboard Features

### Admin Dashboard
- **KPI Summary**: Total income, pending payments, active enrolments, active courses
- **Income Chart**: 12-month income trend visualization
- **Latest Registrations**: Last 10 student registrations
- **Reschedule Requests**: Pending reschedule requests management
- **Today Sessions**: All sessions happening today
- **Latest Tickets**: Recent tickets/chat messages from students

### Instructor Dashboard
- **Today & Tomorrow Sessions**: Sessions scheduled for today and tomorrow
- **My Courses**: Courses being taught with active participants count
- **Reschedule Pending**: Pending reschedule requests to approve/reject
- **Attendance Pending**: Sessions requiring attendance input
- **Latest Messages**: Recent messages from students

### Student Dashboard
- **Continue Learning**: Last learned material with progress
- **Next Session**: Upcoming session details (date, time, mode, link/location)
- **Active Days Counter**: Remaining active days with progress bar
- **Payment Status**: Last payment status with CTA to pay if pending
- **Certificate Ready**: Available certificates for download
- **Chat Shortcut**: Quick access to chat with instructor/admin

## 🔧 Usage

### Base Path Configuration
All dashboard files use a base path relative to the demo1 directory:
```html
<base href="../../../../" />
```

### API Data Placeholders
All dashboard components use placeholders for API data in the format `{{variable_name}}`. Replace these with actual API calls:

**Example:**
```html
<span>{{total_income}}</span>
```

Should be replaced with:
```javascript
// In your backend template engine or frontend framework
const totalIncome = await fetchTotalIncome();
document.querySelector('[data-total-income]').textContent = formatCurrency(totalIncome);
```

### Chart Integration
Charts use Chart.js. Initialize them in the `_scripts.js` files:

```javascript
import { incomeChartConfig } from '../../../../shared/utils/chart-config.js';

const ctx = document.getElementById('kt_income_chart');
const config = {
    ...incomeChartConfig,
    data: {
        ...incomeChartConfig.data,
        datasets: [{
            ...incomeChartConfig.data.datasets[0],
            data: monthlyIncomeData // Your API data
        }]
    }
};
new Chart(ctx, config);
```

### Component Includes
Components are designed to be included via server-side includes or template engines:

```html
<!-- Example: Server-side include -->
<!--#include virtual="src/admin/dashboard/components/kpi-summary.html" -->

<!-- Example: Template engine (PHP) -->
<?php include 'src/admin/dashboard/components/kpi-summary.html'; ?>

<!-- Example: Template engine (Node.js/EJS) -->
<%- include('src/admin/dashboard/components/kpi-summary.html') %>
```

## 📝 Quick Actions

Each role has a quick actions page with common tasks:

### Admin Quick Actions
- Tambah Kursus (Add Course)
- Buat Sesi (Create Session)
- Export Keuangan (CSV) (Export Financial Data)
- Verifikasi Pembayaran Pending (Verify Pending Payment)

### Instructor Quick Actions
- Buat Sesi Baru (Create New Session)
- Input Absensi (Input Attendance)
- Approve/Reject Reschedule

### Student Quick Actions
- Lanjut Belajar (Continue Learning)
- Lihat Jadwal (View Schedule)
- Bayar Sekarang (Pay Now)
- Hubungi Instruktur (Contact Instructor)

## 🎨 Styling

### Shared Styles
Global styles are in `shared/styles/global.css`:
- Dashboard widget styles
- KPI card styles
- Chart container styles
- Table styles
- Badge styles
- Progress bar styles

### Role-Specific Styles
Each role has its own styles in `{role}/dashboard/components/partials/_styles.css`:
- Admin-specific widget styles
- Instructor-specific session card styles
- Student-specific learning card styles

## 🔌 Scripts

### Shared Utilities
- `shared/utils/chart-config.js`: Chart.js configurations
- `shared/utils/formatters.js`: Formatting functions (currency, date, time, etc.)

### Role-Specific Scripts
Each role has its own scripts in `{role}/dashboard/components/partials/_scripts.js`:
- Component initialization
- API calls
- Event handlers
- Data manipulation

## 📋 TODO / Integration Steps

1. **Backend Integration**
   - Replace `{{variable_name}}` placeholders with actual API data
   - Implement API endpoints for dashboard data
   - Add authentication/authorization checks

2. **Template Engine**
   - Set up server-side includes or template engine
   - Configure base path correctly
   - Implement component includes

3. **Chart Data**
   - Connect chart configurations to API endpoints
   - Implement real-time data updates (if needed)
   - Add error handling for chart data

4. **Shared Layout**
   - Create shared header, sidebar, and footer components
   - Implement role-based menu items
   - Add user profile dropdown

5. **Responsive Design**
   - Test on mobile devices
   - Adjust grid layouts for smaller screens
   - Optimize chart rendering for mobile

6. **Testing**
   - Test all dashboard widgets
   - Verify API integrations
   - Test responsive layouts
   - Check browser compatibility

## 🚀 Next Steps

1. Integrate with your backend API
2. Replace placeholder data with real data
3. Implement authentication/authorization
4. Add error handling and loading states
5. Implement real-time updates (if needed)
6. Add unit tests and integration tests
7. Deploy to production

## 📚 Documentation

For more information about Metronic template, visit:
- [Metronic Documentation](https://preview.keenthemes.com/html/metronic/docs)
- [Metronic Components](https://preview.keenthemes.com/html/metronic/docs/base/utilities)

## 📄 License

This dashboard system is built using Metronic template. Please refer to Metronic's license for usage terms.

