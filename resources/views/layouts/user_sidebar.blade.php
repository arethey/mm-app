<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ URL::to('user/dashboard') }}" class="sidebar-brand">M<small>enstrual</small> <span class="font-weight-bold">M<small>onitoring</small></span></a>
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
                <a href="{{ URL::to('user/dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category">General</li>
            <li class="nav-item">
                <a href="{{ URL::to('user/profile') }}" class="nav-link">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ URL::to('user/menstrual') }}" class="nav-link">
                    <i class="link-icon" data-feather="clipboard"></i>
                    <span class="link-title">Menstrual Data</span>
                </a>
            </li>
        </ul>
    </div>
</nav>