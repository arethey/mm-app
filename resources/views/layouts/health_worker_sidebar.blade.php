<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ URL::to('health-worker/dashboard') }}" class="sidebar-brand">M<small>enstrual</small> <span class="font-weight-bold">M<small>onitoring</small></span></a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item">
                <a href="{{ URL::to('health-worker/dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ URL::to('health-worker/feminine-list') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Feminine List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ URL::to('health-worker/calendar') }}" class="nav-link">
                    <i class="link-icon" data-feather="calendar"></i>
                    <span class="link-title">Calendar</span>
                </a>
            </li>
            <li class="nav-item nav-category">Configuration</li>
            <li class="nav-item">
                <a href="{{ URL::to('health-worker/account') }}" class="nav-link">
                    <i class="link-icon" data-feather="settings"></i>
                    <span class="link-title">Account</span>
                </a>
            </li>
        </ul>
    </div>
</nav>